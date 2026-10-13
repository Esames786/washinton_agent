<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Starter posts for the Hello Transport blog (website point 6).
 *
 * Original articles, not copies of ShipA1's: the same text on two sites is treated as
 * duplicate content and neither ranks. Bodies live in resources/blog/*.html so they can
 * be read and edited as HTML; images are theme files under public/ (Blog::isThemeImage).
 *
 * Insert-only, keyed by slug, soft-deleted rows included: a post an admin has edited or
 * deleted is never overwritten or brought back. Safe to re-run.
 *
 * Lives in database/seeds (classmap, global namespace) like the B6 seeders, hence:
 *   composer dump-autoload
 *   php artisan db:seed --class="\HelloBlogPostsSeeder" --force
 */
class HelloBlogPostsSeeder extends Seeder
{
    private const IMG = 'frontend/newtheme-assets/img/';

    /** Listed newest first; the blog index shows them in this order. */
    public const POSTS = [
        [
            'slug'     => 'how-car-shipping-works',
            'title'    => 'How Car Shipping Works: A Step-by-Step Guide',
            'category' => 'Car Shipping',
            'image'    => 'services/car-transports.webp',
            'alt'      => 'Car carrier truck loaded with vehicles on the highway',
            'excerpt'  => 'From the first quote to the final signature: what happens at each stage of a car shipment, and how to make it go smoothly.',
            'meta_title' => 'How Car Shipping Works, Step by Step',
            'meta_desc'  => 'What happens when you ship a car: quote, booking, carrier assignment, pickup inspection, transit and delivery, plus tips for a smoother shipment.',
            'keywords'   => 'car shipping, auto transport, how car shipping works, bill of lading, ship a car',
        ],
        [
            'slug'     => 'heavy-equipment-shipping-guide',
            'title'    => 'Shipping Heavy Equipment: What to Know Before You Book',
            'category' => 'Heavy Equipment',
            'image'    => 'blog/heavy-equipment-on-lowboy.webp',
            'alt'      => 'Excavator loaded on a lowboy trailer',
            'excerpt'  => 'Trailer types, oversize permits and the details a carrier needs to quote your excavator, dozer or tractor accurately.',
            'meta_title' => 'Heavy Equipment Shipping Guide',
            'meta_desc'  => 'How heavy equipment is shipped: the details carriers need, flatbed vs step deck vs lowboy/RGN trailers, oversize permits and how to prepare the machine.',
            'keywords'   => 'heavy equipment shipping, heavy haul, lowboy, RGN trailer, oversize load permits, excavator transport',
        ],
        [
            'slug'     => 'transport-sales-agent-role',
            'title'    => 'What Does a Transport Sales Agent Do?',
            'category' => 'Company News',
            'image'    => 'blog/blog-9.jpg',
            'alt'      => 'Remote sales agents working with headsets',
            'excerpt'  => 'Inside the Hello Transport agent program: what the role involves, who we look for, how agents are paid and how to join.',
            'meta_title' => 'Become a Transport Sales Agent',
            'meta_desc'  => 'What a Hello Transport sales agent does, who we look for, how commission is paid and the six steps from application to your first commission.',
            'keywords'   => 'transport sales agent, freight sales agent, remote sales job, auto transport agent, commission sales',
        ],
        [
            'slug'     => 'open-vs-enclosed-auto-transport',
            'title'    => 'Open vs. Enclosed Auto Transport: Which Should You Choose?',
            'category' => 'Car Shipping',
            'image'    => 'blog/open-vs-enclosed-car-transport.webp',
            'alt'      => 'Cars being loaded into an enclosed car carrier trailer',
            'excerpt'  => 'Both get your car there safely. The difference is protection and cost. Here is how to decide which suits your vehicle.',
            'meta_title' => 'Open vs. Enclosed Auto Transport',
            'meta_desc'  => 'Open or enclosed car transport? Compare protection, cost, availability and loading, and find out which suits your vehicle.',
            'keywords'   => 'open vs enclosed auto transport, enclosed car shipping, open car carrier, classic car transport',
        ],
        [
            'slug'     => 'ltl-vs-ftl-freight',
            'title'    => 'LTL vs. FTL Freight: How to Choose',
            'category' => 'Freight',
            'image'    => 'services/dry-van.webp',
            'alt'      => 'Box truck driving on a mountain highway',
            'excerpt'  => 'Full truckload or less-than-truckload? How each works, when to use it, and what freight class means for your price.',
            'meta_title' => 'LTL vs. FTL Freight: How to Choose',
            'meta_desc'  => 'Less-than-truckload or full truckload? How LTL and FTL work, when each makes sense, NMFC freight classes and accessorial services explained.',
            'keywords'   => 'LTL vs FTL, less than truckload, full truckload, freight class, NMFC, freight shipping',
        ],
        [
            'slug'     => 'how-to-prepare-your-boat-for-transport',
            'title'    => 'How to Prepare Your Boat for Transport',
            'category' => 'Boat Transport',
            'image'    => 'services/BOAT-BANNER.webp',
            'alt'      => 'Boat on a trailer being towed by a pickup truck',
            'excerpt'  => 'Measurements to have ready, how to prepare the boat and its trailer, and what to check at pickup and delivery.',
            'meta_title' => 'How to Prepare Your Boat for Transport',
            'meta_desc'  => 'A practical boat transport checklist: measurements for your quote, cleaning and draining, securing gear, trailer checks and inspection at pickup.',
            'keywords'   => 'boat transport, boat shipping, prepare boat for transport, boat hauling, oversize boat',
        ],
        [
            'slug'     => 'roro-shipping-explained',
            'title'    => 'RoRo Shipping Explained: How to Ship a Vehicle Overseas',
            'category' => 'RoRo / International',
            'image'    => 'services/roro-banner.webp',
            'alt'      => 'Cars on the deck of a roll-on/roll-off ferry at sunset',
            'excerpt'  => 'How roll-on/roll-off shipping works, how it compares with containers, and the documents you need to export a vehicle.',
            'meta_title' => 'RoRo Shipping Explained',
            'meta_desc'  => 'How RoRo vehicle shipping works, RoRo vs container shipping, export documents for U.S. vehicles and how to prepare your vehicle for the port.',
            'keywords'   => 'RoRo shipping, roll on roll off, ship car overseas, vehicle export, international car shipping',
        ],
        [
            'slug'     => 'rv-transport-options-explained',
            'title'    => 'RV Transport: Your Options Explained',
            'category' => 'RV Transport',
            'image'    => 'blog/rv-transport.webp',
            'alt'      => 'Large motorhome parked on grass',
            'excerpt'  => 'Drive-away, tow-away or trailer transport: the three ways to move an RV, and how to prepare yours.',
            'meta_title' => 'RV Transport Options Explained',
            'meta_desc'  => 'Drive-away, tow-away or flatbed: how RVs are transported, what you need for a quote and how to prepare a motorhome, travel trailer or fifth wheel.',
            'keywords'   => 'RV transport, motorhome transport, travel trailer shipping, fifth wheel transport, RV shipping',
        ],
        [
            'slug'     => 'shipping-refrigerated-freight',
            'title'    => 'Shipping Temperature-Sensitive Freight: A Guide to Reefer Transport',
            'category' => 'Freight',
            'image'    => 'services/ReeferTruck3.webp',
            'alt'      => 'Refrigerated truck on the highway',
            'excerpt'  => 'How reefer trailers keep freight in range, what to tell us when you book, and best practices for a safe cold chain.',
            'meta_title' => 'Reefer Transport: Shipping Cold Freight',
            'meta_desc'  => 'How refrigerated (reefer) freight works: continuous vs cycle mode, what to include in a quote request and best practices for shippers.',
            'keywords'   => 'reefer transport, refrigerated freight, temperature controlled shipping, cold chain, reefer trailer',
        ],
        [
            'slug'     => 'vehicle-preparation-checklist',
            'title'    => 'Vehicle Preparation Checklist: 12 Steps Before Pickup',
            'category' => 'Tips & Guides',
            'image'    => 'blog/car-on-flatbed.webp',
            'alt'      => 'SUV secured on a flatbed truck',
            'excerpt'  => 'Twelve quick steps that protect your vehicle, speed up the handover and give you a clear record at delivery.',
            'meta_title' => 'Vehicle Prep Checklist Before Pickup',
            'meta_desc'  => 'Twelve steps to prepare your car for shipping: wash and photograph it, remove belongings and toll tags, check fuel, leaks, battery and more.',
            'keywords'   => 'prepare car for shipping, vehicle preparation checklist, car shipping tips, auto transport checklist',
        ],
    ];

    public function run()
    {
        $now     = now();
        $added   = 0;
        $skipped = 0;

        foreach (self::POSTS as $i => $p) {
            // Includes soft-deleted rows, so a post an admin removed stays removed.
            if (DB::table('blogs')->where('slug_name', $p['slug'])->exists()) {
                $skipped++;
                continue;
            }

            // A minute apart, so the listing order is the order above.
            $at = $now->copy()->subMinutes($i);

            DB::table('blogs')->insert([
                'user_id'                => null,
                'post_name'              => $p['title'],
                'slug_name'              => $p['slug'],
                'post_short_description' => $p['excerpt'],
                'post_description'       => trim(file_get_contents(resource_path('blog/' . $p['slug'] . '.html'))),
                'category'               => $p['category'],
                'blog_author'            => 'Hello Transport Team',
                'post_image'             => self::IMG . $p['image'],
                'image_alt'              => $p['alt'],
                'image_title'            => $p['title'],
                'image_caption'          => null,
                'meta_title'             => $p['meta_title'],
                'meta_description'       => $p['meta_desc'],
                'meta_keyword'           => $p['keywords'],
                'canonical_url'          => null,   // the view falls back to the post's own URL
                'blog_schema'            => null,   // the view generates BlogPosting JSON-LD
                'status'                 => 1,
                'scheduled_at'           => null,
                'created_at'             => $at,
                'updated_at'             => $at,
            ]);
            $added++;
        }

        $this->command?->info("Blog posts: {$added} added, {$skipped} already present.");
    }
}
