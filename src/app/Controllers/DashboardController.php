<?php

namespace App\Controllers;

use App\Core\Controller;

class  DashboardController extends Controller
{
    public function index(): mixed
    {
        return view("dashboard");
    }
}

