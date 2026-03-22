<?php include_once viewPath() . "layouts/header.php"  ?>
<div style="padding: 1rem; background-color: black;">
    <h3 style="color: red;">Error</h3>
    <p style="color: #08d308;"><?= $data["message"] ?></p>
</div>
<?php include_once viewPath() . "layouts/footer.php"  ?>
