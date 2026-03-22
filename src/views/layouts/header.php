<?php
    $current_page = explode("/", $_SERVER["REQUEST_URI"])[count(explode("/", $_SERVER["REQUEST_URI"]) ) -1];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLM</title>
    <link rel="stylesheet" href="<?=  assetPath() ."/bootstrap.min.css" ?>" />
</head>

<body data-bs-theme="dark">

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
          <?php
            $isAuth = \App\Core\Auth::check();
          ?>
          <?php if($isAuth): ?>
            <a class="nav-link <?= $current_page === 'dashboard' ? 'active' :'' ?>" href="./dashboard">Home</a>
            <a class="nav-link <?= $current_page === 'products' ? 'active' :'' ?>" href="./products">Products</a>
            <a class="nav-link <?= $current_page === 'team' ? 'active' :'' ?>" href="./team">Team</a>
            <a class="nav-link <?= $current_page === 'my' ? 'active' :'' ?>" href="./my">My</a>
            <a class="nav-link <?= $current_page === 'recharge' ? 'active' :'' ?>" href="./recharge">Recharge</a>
            <a class="nav-link <?= $current_page === 'withdraw' ? 'active' :'' ?>" href="./withdraw">Withdraw</a>
            <a class="nav-link <?= $current_page === 'account' ? 'active' :'' ?>" href="./account">Account</a>
            <a class="nav-link <?= $current_page === 'signin' ? 'active' :'' ?>" href="./signin">Daily Sign In</a>
            <a class="nav-link <?= $current_page === 'getbonus' ? 'active' :'' ?>" href="./getbonus">Bonus</a>
            <a class="nav-link <?= $current_page === 'records' ? 'active' :'' ?>" href="./records">Records</a>
            <a class="nav-link <?= $current_page === 'income' ? 'active' :'' ?>" href="./income">Income</a>
            <a class="nav-link <?= $current_page === 'password' ? 'active' :'' ?>" href="./password">Change Password</a>
            <a class="nav-link <?= $current_page === 'packagehistory' ? 'active' :'' ?>" href="./packagehistory">Product History</a>
            <a class="nav-link <?= $current_page === 'logout' ? 'active' :'' ?>" href="./logout">Logout</a>
          <?php else: ?>
            <a class="nav-link <?= $current_page === '' ? 'active' :'' ?>" href="./">Home</a>
            <a class="nav-link <?= $current_page === 'login' ? 'active' :'' ?>" href="./login">Login</a>
            <a class="nav-link <?= $current_page === 'register' ? 'active' :'' ?>" href="./register">Register</a>
          </div>
        <?php endif; ?>
        </div>
      </div>
    </nav>

    <div class="container">
