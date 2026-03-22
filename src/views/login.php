<?php include_once viewPath() . "layouts/header.php"  ?>
<form action="/login" method="post">
    <h1>Log In</h1>
    <?php if(!empty($data["errors"])): ?>
    <?php foreach($data["errors"] as $error): ?>
    <?php foreach($error as $newError): ?>
        <div class="alert alert-danger">
            <span ><?= $newError ?></span>
        </div>
    <?php endforeach; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group mb-3">
        <label class="form-label" for="mobile">Mobile: </label>
        <input type="tel" class="form-control" name="mobile" id="mobile" required/>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="password">Password: </label>
        <input type="password" class="form-control" name="password" id="password" required/>
    </div>
    <button class="btn btn-primary" type="submit">Login</button>
    <div class="links">
        <a href="./forgot.php">Forgot Password</a>
        <a href="/register">Register Account</a>
    </div>
</form>

<?php include_once viewPath() . "layouts/footer.php"  ?>
