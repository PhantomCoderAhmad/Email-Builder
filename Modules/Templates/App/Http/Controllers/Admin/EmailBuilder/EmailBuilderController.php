<?php

namespace Modules\Templates\App\Http\Controllers\Admin\EmailBuilder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Templates\App\Models\EmailBuilderTemplate;

class EmailBuilderController extends Controller
{
    public function index()
    {
        try {
            // Send the inertia response
            $templates = EmailBuilderTemplate::all()->toArray() ?? [];

            return inertia('Emailbuilder', [
                'templates' => $templates
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function saveTemplate(Request $request)
    {
        try {
            // Create new template
            $template = new EmailBuilderTemplate();
            $template->name = $request->templateName;
            $template->content_path = '-';
            if ($template->save()) {
                $template->content = [
                    'html' => $request->htmlData, 
                    'json' => $request->jsonData
                ];
                $template->save();
            }
            return redirect()->route('admin.email-builder');
        } catch (\Exception $e) {
            $errors = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ];
            return response()->json($errors, 500);
        }
    }

    public function updateTemplate(Request $request)
    {
        try {
            // Check if the template already exists
            $template = EmailBuilderTemplate::find($request->templateId);

            if ($template) {
                // Update existing template
                $template->content = [
                    'html' => $request->htmlData,
                    'json' => $request->jsonData
                ];
                $template->save();
            }
            return redirect()->route('admin.email-builder');
        } catch (\Exception $e) {
            $errors = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ];
            return response()->json($errors, 500);
        }
    }
}