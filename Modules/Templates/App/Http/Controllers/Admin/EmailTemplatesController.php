<?php

namespace Modules\Templates\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailTemplatesController extends Controller
{
    public function index(Request $request)
    {
        return view('templates::tailwind.admin.email-templates.index');
    }
}