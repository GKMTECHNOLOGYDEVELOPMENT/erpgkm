<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class ContactsController extends Controller
{
    public function index()
    {
        return view('apps.contacts'); // Vista para contacts
    }
}
