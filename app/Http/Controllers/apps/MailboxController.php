<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class MailboxController extends Controller
{
    public function index()
    {
        return view('apps.mailbox'); // Vista para mailbox
    }
}
