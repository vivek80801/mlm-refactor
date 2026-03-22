<?php

use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\ProductsController;
use App\Controllers\TeamController;
use App\Controllers\MyController;
use App\Controllers\RechargeController;
use App\Controllers\WithdrawController;
use App\Controllers\AccountController;
use App\Controllers\SigninController;
use App\Controllers\GetbonusController;
use App\Controllers\RecordsController;
use App\Controllers\IncomeController;
use App\Controllers\PasswordController;
use App\Controllers\PackagehistoryController;
use App\Core\Router;
use App\Core\Session;


$session = new Session();
$session->startSession();
$method = $_SERVER["REQUEST_METHOD"];
$url = parse_url($_SERVER["REQUEST_URI"]);
$raw_data = explode("&", $_SERVER["QUERY_STRING"]);
$data = [];

if(count($raw_data) > 0){
    foreach ($raw_data as $single_raw_data) {
        $new_data_parts = explode("=", $single_raw_data);
        $key = $new_data_parts[0];
        $value = $new_data_parts[1];
        $data[$key] = htmlspecialchars($value);
    }
}

$router = new Router();

// =============================================

$router->get("/", [HomeController::class, "index"]);
$router->get("/login", [LoginController::class, "index"]);
$router->post("/login", [LoginController::class, "login"]);
$router->get("/logout", [LoginController::class, "logout"])->middleware("auth");
$router->get("/register", [RegisterController::class, "index"]);
$router->post("/register", [RegisterController::class, "register"]);
$router->get("/dashboard", [DashboardController::class, "index"])->middleware("auth");
$router->get('/products',[ProductsController::class, "index"])->middleware("auth");
$router->get('/product_detail',[ProductsController::class, "productDetail"])->middleware("auth");
$router->post('/product_rental',[ProductsController::class, "productRental"])->middleware("auth");
$router->get('/team',[TeamController::class, "index"])->middleware("auth");
$router->get('/my',[MyController::class, "index"])->middleware("auth");
$router->get('/recharge',[RechargeController::class, "index"])->middleware("auth");
$router->get('/withdraw',[WithdrawController::class, "index"])->middleware("auth");
$router->get('/account',[AccountController::class, "index"])->middleware("auth");
$router->get('/signin',[SigninController::class, "index"])->middleware("auth");
$router->get('/getbonus',[GetbonusController::class, "index"])->middleware("auth");
$router->get('/records',[RecordsController::class, "index"])->middleware("auth");
$router->get('/income',[IncomeController::class, "index"])->middleware("auth");
$router->get('/password',[PasswordController::class, "index"])->middleware("auth");
$router->get('/packagehistory',[PackagehistoryController::class, "index"])->middleware("auth");

// =============================================

$router->resolve($url["path"], $method);

