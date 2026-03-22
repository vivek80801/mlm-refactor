<?php include_once viewPath() . 'layouts/header.php'  ?>
<?php
    $product = $data["product"];
    $is_hero_purchased_product = $data["is_hero_purchased_product"];
?>
<div class="container my-3">
    <form method="post" action="./product_detail?id=<?= $product["id"] ?>" >
        <h1>Rental Device</h1>
            <img src="./assets/uploads/products/<?= $product["image"] ?>" width="100" />
           <h3 class="my-3"><?= $product["product_name"] ?></h3>
        <input type="hidden" >
        <ul class="list-group">
         <li class="list-group-item"> The Price: <?= $product["price"] ?></li>
          <li class="list-group-item">Cycle: <?= $product["cycle"] ?> Day</li>
          <li class="list-group-item">Daily Income: <?= $product["daily_income"] ?></li>
          <li class="list-group-item">Total Income: <?= $product["total_income"] ?></li>
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
