<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->get();
        return view('admin.email-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.email-templates.builder');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content_html' => 'required',
            'content_json' => 'nullable',
        ]);

        EmailTemplate::create($request->all());

        return response()->json(['success' => true, 'message' => 'Template saved successfully']);
    }

    public function show(EmailTemplate $emailTemplate)
    {
        return response()->json([
            'success' => true,
            'data' => $emailTemplate
        ]);
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.builder', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        if ($request->has('_partial')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
            ]);
            $emailTemplate->update($request->only(['name', 'subject']));
            return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content_html' => 'required',
            'content_json' => 'nullable',
        ]);

        $emailTemplate->update($request->all());

        return response()->json(['success' => true, 'message' => 'Template updated successfully']);
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        return back()->with('success', 'Template deleted successfully');
    }
}
