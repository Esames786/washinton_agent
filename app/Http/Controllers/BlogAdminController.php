<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Where posts for the public Hello Transport blog are written (website point 6).
 *
 * Admins and managers author, matching who may edit guides and auction instructions
 * elsewhere in the portal — and who ShipA1 lets write its own blog.
 */
class BlogAdminController extends Controller
{
    private const EDITORS = [1, 9];   // admin, manager

    public const CATEGORIES = [
        'Car Shipping', 'Heavy Equipment', 'Boat Transport', 'RV Transport', 'Freight',
        'RoRo / International', 'Tips & Guides', 'Company News',
    ];

    private function authorise(): void
    {
        abort_unless(in_array((int) Auth::user()->role, self::EDITORS, true), 403,
            'Only admins and managers can manage the blog.');
    }

    public function index()
    {
        $this->authorise();

        $posts = Blog::orderByDesc('id')->paginate(20);

        return view('main.blog_admin.index', compact('posts'));
    }

    public function create()
    {
        $this->authorise();

        return view('main.blog_admin.form', [
            'post'       => new Blog(['status' => 0, 'blog_author' => Auth::user()->name]),
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorise();

        $data = $this->validated($request);
        $data['user_id']   = Auth::id();
        $data['slug_name'] = Blog::uniqueSlug($request->input('slug_name') ?: $data['post_name']);

        if ($request->hasFile('post_image')) {
            $data['post_image'] = $request->file('post_image')->store('blog', 'public');
        }

        $post = Blog::create($data);

        return redirect()
            ->route('blog-admin.edit', $post->id)
            ->with('success', $post->is_live
                ? 'Post published — it is live at /blog/' . $post->slug_name
                : 'Post saved as ' . strtolower($post->state) . '.');
    }

    public function edit($id)
    {
        $this->authorise();

        return view('main.blog_admin.form', [
            'post'       => Blog::findOrFail($id),
            'categories' => self::CATEGORIES,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorise();

        $post = Blog::findOrFail($id);
        $data = $this->validated($request, $post->id);

        // Only re-slug when the admin actually changes it — a published post's URL is
        // what search engines have indexed, so it must not drift with every title edit.
        if ($request->filled('slug_name') && $request->input('slug_name') !== $post->slug_name) {
            $data['slug_name'] = Blog::uniqueSlug($request->input('slug_name'), $post->id);
        }

        if ($request->hasFile('post_image')) {
            if ($post->post_image && ! Str::startsWith($post->post_image, 'http')) {
                Storage::disk('public')->delete($post->post_image);
            }
            $data['post_image'] = $request->file('post_image')->store('blog', 'public');
        }

        $post->update($data);

        return back()->with('success', $post->is_live
            ? 'Post updated — live at /blog/' . $post->slug_name
            : 'Post updated (' . strtolower($post->state) . ').');
    }

    public function destroy($id)
    {
        $this->authorise();

        // Soft delete, as ShipA1 does — the post vanishes from the site but can be recovered.
        Blog::findOrFail($id)->delete();

        return redirect()->route('blog-admin.index')->with('success', 'Post removed from the site.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'post_name'              => 'required|string|max:255',
            'slug_name'              => 'nullable|string|max:255',
            'post_short_description' => 'nullable|string|max:1000',
            'post_description'       => 'required|string',
            'category'               => 'nullable|string|max:100',
            'blog_author'            => 'nullable|string|max:150',
            'post_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_alt'              => 'nullable|string|max:255',
            'image_title'            => 'nullable|string|max:255',
            'image_caption'          => 'nullable|string|max:255',
            'meta_title'             => 'nullable|string|max:255',
            'meta_description'       => 'nullable|string|max:500',
            'meta_keyword'           => 'nullable|string|max:255',
            'canonical_url'          => 'nullable|url|max:255',
            'blog_schema'            => 'nullable|string',
            'status'                 => 'required|in:0,1',
            'scheduled_at'           => 'nullable|date',
        ], [
            'post_description.required' => 'The post needs some content.',
            'post_image.max'            => 'The image must be under 4 MB.',
        ]);

        unset($data['post_image'], $data['slug_name']);   // handled by the caller

        $data['post_description'] = $this->cleanHtml($data['post_description']);
        $data['blog_schema']      = $this->cleanSchema($data['blog_schema'] ?? null);

        return $data;
    }

    /**
     * The body is rendered as HTML on the public site. Admins are trusted, but a pasted
     * snippet can still carry script — so script tags, inline event handlers and
     * javascript: links are removed. Formatting, images and embeds are kept.
     */
    private function cleanHtml(string $html): string
    {
        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);
        $html = preg_replace('#\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);
        $html = preg_replace('#(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2#i', '$1=$2#$2', $html);

        return $html;
    }

    /**
     * JSON-LD sits inside a <script> tag on the post page. Accepting only valid JSON and
     * re-encoding it means nothing pasted here can close that tag and run as script.
     */
    private function cleanSchema(?string $schema): ?string
    {
        $schema = trim((string) $schema);
        if ($schema === '') {
            return null;
        }

        // Admins often paste the whole <script> block from a generator; take the JSON out.
        $schema = trim(preg_replace('#^<script[^>]*>|</script>$#i', '', $schema));

        $decoded = json_decode($schema, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'blog_schema' => 'The schema must be valid JSON-LD. Leave it blank if you do not have one.',
            ]);
        }

        return json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }
}
