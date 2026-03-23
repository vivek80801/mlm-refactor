<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\User;
use App\Services\UserService;

class RegisterController extends Controller
{

    public function __construct
    (
        private UserService $userService
    )
    { }

    public function index(Request $request): mixed
    {
        $inviteCode = $request->input("inviteCode") ? $request->input("inviteCode") : "";
        return view("register", [
            "inviteCode" => $inviteCode
        ]);
    }

    public function register
    (
        Request $request
    ): mixed
    {
        $errors = Validator::validate([
            "mobile" => "required|digit:10",
            "password" => "required|min:3|max:20",
            "referred_by" => "required"
        ], $request);

        if(!empty($errors))
        {
            return view("register", [
                "errors" => $errors
            ]);
        }

        $user = User::where("mobile", $request->input("mobile"));

        if($user)
        {
            return view("register", [
                "errors" => "user already exists"
            ]);
        }

        $this->userService
            ->register($request);

        return redirect("/login");
    }
}
