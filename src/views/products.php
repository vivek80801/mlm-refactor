<?php include_once viewPath() . 'layouts/header.php'  ?>
<?php $products = $data["products"]; ?>
  <?php if(count($products) > 0): ?>
  <div class="row">
      <?php foreach ($products as $key => $product): ?>
      <?php if($product->status === "active"): ?>
      <div class="card py-3 col-md-3 m-3" style="width: 18rem;">
        <img class="mx-5" src="./assets/uploads/products/<?= $product->image ?>" width="100" height="100" />
            <div class="card-body">
          <h3 class="card-title">Share Rental</h3>

        <ul class="list-group list-group-flush">
          <li class="list-group-item">The Price: <?= $product->price ?></li>
            <li class="list-group-item">Cycle: <?= $product->cycle ?>Day</li>
            <li class="list-group-item">Daily income: <?= $product->daily_income ?></li>
            <li class="list-group-item">Total income: <?= $product->total_income ?></li>
        </ul>

     <a href="./product_detail?id=<?= $product->id ?>" style="width:100%" class="rental btnInvest account-btn">Rental</a>
    </div>
</div>

                    <?php endif ?>
                    <?php endforeach ?>
</div>
                    <?php endif ?>
<?php include_once viewPath() . 'layouts/footer.php'  ?>
