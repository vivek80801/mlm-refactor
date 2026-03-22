<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        $inviteCode = $request->input("inviteCode") ? $request->input("inviteCode") : "";
        return view("register", [
            "inviteCode" => $inviteCode
        ]);
    }

    public function register()
    {
        dd('index');
    }
}
       
