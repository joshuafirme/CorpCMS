<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUsMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $data = ContactUsMessage::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.messages.list', compact('data'));
    }
}
