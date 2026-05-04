<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailTemplate;
use App\EmailHistory;
use Auth;
use App\Mail\CustomMail;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Http\Response;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $emailTemplates = EmailTemplate::all();
        return view('emails.templates.index', compact('emailTemplates'));
    }

    public function create()
    {
        return view('emails.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'banner' => 'nullable|max:2048',
        ]);

        // Set the 'user_id' based on the authenticated user
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        // Check if a file is provided
        if ($request->hasFile('banner')) {
            // Get the file from the request
            $file = $request->file('banner');

            // Set a unique name for the file
            $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move the file to the storage path
            $file->move(public_path('storage/banners'), $fileName);

            // Set the file path in the data array
            $data['banner'] = 'storage/banners/' . $fileName;
        }

        // Create the EmailTemplate
        EmailTemplate::create($data);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function show($id)
    {
        $emailTemplate = 'abc';
        return view('emails.templates.show', compact('emailTemplate'));
    }

    public function edit($id)
    {
        $emailTemplate = EmailTemplate::find($id);
        return view('emails.templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ]);

        $emailTemplate = EmailTemplate::find($id);

        if ($emailTemplate) {
            // Delete previous file if exists
            if ($request->hasFile('banner') && !empty($emailTemplate->banner)) {
                $this->deleteFile($emailTemplate->banner);
            }

            // Update EmailTemplate data
            $emailTemplate->update($request->except('banner'));

            // Handle banner file upload
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/banners'), $fileName);
                $emailTemplate->banner = 'storage/banners/' . $fileName;
                $emailTemplate->save();
            }
        }

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template updated successfully');
    }

    private function deleteFile($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function destroy($id)
    {
        $emailTemplate = EmailTemplate::find($id);

        if ($emailTemplate) {
            $emailTemplate->delete();
            return redirect()->route('email-templates.index')->with('success', 'Email template deleted successfully');
        } else {
            // Handle the case where the EmailTemplate with the given $id is not found
            return redirect()->route('email-templates.index')->with('error', 'Email template not found');
        }
    }

    public function sendEmail(Request $request)
    {
        try {
            $emailTemplate = EmailTemplate::first();

            if (!$emailTemplate) {
                return response()->json(['error' => 'Email template not found'], Response::HTTP_NOT_FOUND);
            }

            $recipientEmail = $request->email;

            if (empty($recipientEmail)) {
                return response()->json(['error' => 'Recipient email is required'], Response::HTTP_BAD_REQUEST);
            }

            $emailsSentLastHour = EmailHistory::where('user_id', Auth::id())
                ->where('created_at', '>=', now()->subHour())
                ->count();

            if ($emailsSentLastHour >= 30) {
                return response()->json(['error' => 'You have reached the email limit of 30 emails per hour.'], Response::HTTP_TOO_MANY_REQUESTS);
            }

            $emailHistory = new EmailHistory;
            $emailHistory->user_id = Auth::id();
            $emailHistory->template_id = $emailTemplate->id;
            $emailHistory->recipient = $recipientEmail;
            $emailHistory->save();

            $content = $emailTemplate->description;
            $banner = $emailTemplate->banner;

            Mail::to($recipientEmail)->send(new CustomMail($content, $banner));

            return response()->json(['message' => 'Email sent successfully!'], Response::HTTP_OK);

        } catch (Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to send email. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function sendEmail2(Request $request)
    {
        try {
            if(!empty($request->template_id)) {
                $emailTemplate = EmailTemplate::find($request->template_id);

                if (!$emailTemplate) {
                    return response()->json(['error' => 'Email template not found'], Response::HTTP_NOT_FOUND);
                }

                $recipientEmail = $request->email;

                if (empty($recipientEmail)) {
                    return response()->json(['error' => 'Recipient email is required'], Response::HTTP_BAD_REQUEST);
                }

                $emailsSentLastHour = EmailHistory::where('user_id', Auth::id())
                    ->where('created_at', '>=', now()->subHour())
                    ->count();

                if ($emailsSentLastHour >= 30) {
                    return response()->json(['error' => 'You have reached the email limit of 30 emails per hour.'], Response::HTTP_TOO_MANY_REQUESTS);
                }

                $emailHistory = new EmailHistory;
                $emailHistory->user_id = Auth::id();
                $emailHistory->template_id = $emailTemplate->id;
                $emailHistory->recipient = $recipientEmail;
                $emailHistory->save();

                $content = $emailTemplate->description;
                $banner = $emailTemplate->banner;

                Mail::to($recipientEmail)->send(new CustomMail($content, $banner));

                return response()->json(['message' => 'Email sent successfully!'], Response::HTTP_OK);
            }

        } catch (Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to send email. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function getEmailTemplates()
    {
        $templates = EmailTemplate::select('id', 'title')->where('type',2)->get();
        return response()->json(['templates' => $templates]);
    }

}
