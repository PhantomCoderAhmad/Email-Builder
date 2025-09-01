<?php

namespace Modules\Templates\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Templates\App\Models\EmailTrigger;

class EmailTriggerController extends Controller
{
    public function index(Request $request)
    {
        return view('templates::tailwind.admin.email-triggers.index');
    }
    public function edit(EmailTrigger $trigger)
    {
        return view('templates::tailwind.admin.email-triggers.edit',compact('trigger'));
    }
}