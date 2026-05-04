<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = Template::all();
        $data = [
            'name',
            'email',
            'ophone',
        ];

        return view('main.phone_quote.templates.index', compact('templates', 'data'));

    }

    public function template_datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Template::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    if(!empty($row->template_image)) {
                        $image = url('template_images') . '/' . $row->template_image;
                    }else{
                        $image = null;
                    }
                    return '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $row->id . '" data-name="' . $row->name . '" data-description="' . $row->description . '" data-status="' . $row->status . '" data-notification_type="' . $row->notification_type . '"
                     data-template_image="' . $image. '"
                >Edit</button>';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Email</span>'
                        : '<span class="badge bg-info">SMS</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }



    public function view_template_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'notification_type' => 'required',
            'status' => 'required|in:1,2'
        ]);

        $data = $request->only(['name', 'description', 'status','notification_type']);

        // Handle file upload if present
        if ($request->hasFile('template_image')) {
            $image = $request->file('template_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('template_images'), $imageName);
            $data['template_image'] = $imageName;
        }

        if (!empty($request->input('id', 0))) {
            // Update the existing template
            $template = Template::findOrFail($request->id);

            // If new image uploaded, delete old image (optional)
            if (isset($data['template_image']) && $template->template_image) {
                $oldPath = public_path('template_images/' . $template->template_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $template->update($data);
            return redirect()->route('view_template')->with('success', 'Template updated successfully.');
        } else {
            // Create a new template
            Template::create($data);
            return redirect()->route('view_template')->with('success', 'Template created successfully.');
        }
    }
}
