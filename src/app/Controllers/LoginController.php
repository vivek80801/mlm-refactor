<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;

class LoginController extends Controller
{
    public function index(): null
    {
        return view("login");
    }

    public function login
    (
        Request $request
    ): null
    {
        $errors = Validator::validate([
            "mobile" => "required|digit:10",
            "password" => "required|min:3|max:20"
        ], $request);

        if(!empty($errors))
        {
            return view("login", [
                "errors" => $errors
            ]);
        }

        if(
            Auth::attempts(
                $request->input("mobile"),
                $request->input("password")
            )
        )
        {
            return redirect("/dashboard");
        } else {
            return view("login", [
                "errors" => [
                    ["mobile or password is wrong"]
                ]
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect("/");
    }
}
