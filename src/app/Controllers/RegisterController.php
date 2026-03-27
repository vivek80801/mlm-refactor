<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Exceptions\AppException;
use App\Core\Request;
use App\Core\Validator;
use App\Services\UserService;
use Throwable;

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

         try {
            $this->userService->register($request);

            return redirect("/login");

        } catch (AppException $e) {
            return view("register", [
                "errors" => $e->getMessage()
            ]);

        } catch (Throwable $e) {
            return view("register", [
                "errors" => "Something went wrong"
            ]);
        }

        return redirect("/login");
    }
}
