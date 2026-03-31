<?php include_once viewPath() . 'layouts/header.php'  ?>
<?php
    $product = $data["product"];
    $is_hero_purchased_product = $data["is_product_can_be_purchased"];
?>
<div class="container my-3">
    <form method="post" action="/product_rental" >
        <h1>Rental Device</h1>
        <?php if(!empty($data["errors"])): ?>
            <div class="alert alert-danger">
                <span ><?= $data["errors"] ?></span>
            </div>
        <?php endif; ?>
            <img src="./assets/uploads/products/<?= $product->image ?>" width="100" />
           <h3 class="my-3"><?= $product->product_name ?></h3>
        <input type="hidden" name="product_id" value="<?= $product->id ?>" >
        <ul class="list-group">
            <li class="list-group-item"> The Price: &#8377;<?= $product->price ?></li>
            <li class="list-group-item">Cycle: <?= $product->cycle ?> Day</li>
            <li class="list-group-item">Daily Income: &#8377;<?= $product->daily_income ?></li>
            <li class="list-group-item">Total Income: &#8377;<?= $product->total_income ?></li>
        </ul>
</br>
             <?php if($is_hero_purchased_product): ?>
           <button type="submit" name="btnSubmit" class="btn btn-primary" >Rental</button>
           <?php else: ?>
           <button type="button"  disabled name="btnSubmit"  class="btn btn-primary">Already Purchased</button>
           <?php endif ?>
    </form>
</div>
<?php include_once viewPath() . 'layouts/footer.php'  ?>
