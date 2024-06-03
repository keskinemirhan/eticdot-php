<?php
include_once "views/_prodcard.php";
include_once "service/dbconnect.php";

$products = $stmt_execute(
  "SELECT 
p.id,
p.name,
p.price,
p.prevPrice,
p.image,
v.name AS vendorName
FROM 
product p, favorite f, vendor v where p.id = f.prodId AND v.id = p.vendorId AND f.userId = ?; ",
  "s",
  $userId
);
?>
<div class="container mx-auto">
  <div class="fav-header">
    <h1>Favorites</h1>
  </div>
  <div class="fav-items">
    <?php
    while ($product = $products->fetch_assoc()) {
      $c_prod_card(
        $product["id"],
        $product["name"],
        $product["price"],
        $product["prevPrice"],
        $product["vendorName"],
        $product["image"],
        true
      );
    }
    ?>
  </div>
</div>
<style>
  .fav-items {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }

  .fav-header h1 {
    text-align: center;
    font-size: 32px;
    margin: 20px;
  }

  .empty-favorites {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-top: 40px;
  }
</style>

<script>
</script>