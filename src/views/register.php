<?php include_once viewPath() . 'layouts/header.php'  ?>

<form action="post">
    <h1>Register</h1>

<?php if(strlen($data["inviteCode"]) <= 0): ?>
<div class="alert alert-danger">
    <span>Please, Enter valid invite Code</span>
</div>
<?php endif ?>

    <div class="form-group mb-3">
        <label class="form-label" for="mobile">Mobile: </label>
        <input class="form-control" type="tel" name="mobile" id="mobile" required/>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="password">Password: </label>
        <input class="form-control" type="tel" name="password" id="password" required/>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="invite_code">Invite Code: </label>
        <input class="form-control" type="text" name="referred_by" value="<?= $data["inviteCode"] ?>" id="invite_code" required/>
    </div>
    <button class="btn btn-primary" type="submit">Login</button>
    <div class="links">
        <a href="/login">Return To Login</a>
    </div>

</form>

<?php include_once viewPath() . 'layouts/footer.php'  ?>
