<?php
include_once "service/utils.php";
include_once "service/user-auth-utils.php";
include_once "service/dbconnect.php";
$messages = [];
$userId = $getUserLoginInfo()->userId;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
  if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
  }
  session_destroy();
  redirect(".");
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isblank_post("address")) {
    $stmt_execute(
      "update user set address = ? where id = ?",
      "ss",
      $_POST["address"],
      $userId
    );
    array_push($messages, "Updated Address");
  }
  if (!isblank_post("phoneNumber")) {
    $stmt_execute(
      "update user set phoneNumber = ? where id = ?",
      "ss",
      $_POST["phoneNumber"],
      $userId
    );
    array_push($messages, "Updated Phone Number");
  }
}
$user = $stmt_execute(
  "select email, name, surname, phoneNumber, address from user where id = ?",
  "s",
  $userId
)->fetch_assoc();

$purchases = $stmt_execute(
  "select id, total, address, date_format(createdAt, '%Y-%m-%d at %H:%i') as createdAt from purchase where userId = ? order by createdAt desc",
  "s",
  $userId
)->fetch_all(MYSQLI_ASSOC);



?>
<div class="profile-container container mx-auto">
  <h1 class="p-header">Profile</h1>
  <div class="p-wrapper">
    <div class="picname">
      <div class="profile-pic">
        <img src="images/profile.png" alt="" srcset="" />
      </div>
      <div class="profile-ns">
        <div class="profile-name"><?php echo $user["name"] ?></div>
        <div class="profile-surname"><?php echo $user["surname"] ?></div>
      </div>
    </div>
    <form method="post" action="profile.php" class="profile-info">
      <div class="p-info">
        <div class="pi-name">Email</div>
        <div class="pi-content p-email"><?php echo $user["email"] ?></div>
      </div>
      <div class="p-info">
        <div class="pi-name">Address</div>
        <input class="pi-content" name="address" type="text" value="<?php echo $user["address"] ?>">
      </div>
      <div class=" p-info">
        <div class="pi-name">Phone Number</div>
        <input class="pi-content" name="phoneNumber" type="tel" value="<?php echo $user["phoneNumber"] ?>">
      </div>
      <button class="auth-submit bg-success" type="submit">Update</button>
    </form>
    <div class="d-flex justify-center">
      <form action="profile.php" method="post">
        <button type="submit" value="true" name="logout" class="auth-submit s-logout">Logout</button>
      </form>
    </div>
  </div>
</div>
<div class="order-wrapper container mx-auto">
  <div class="order-frame">
    <h2 class="order-title">Orders</h2>
    <div class="orders-list">
      <?php foreach ($purchases as $purchase) { ?>
        <div class="order-item">
          <div class="order-product-list">
            <?php
            $purchaseItems = $stmt_execute(
              "select p.id as id , p.image as image, p.name as prodName,
           pu.amount as amount, pu.status as status,
           pu.priceSnapshot as price  from product p,
           purchase_item pu where pu.productId = p.id and
           pu.purchaseId = ?",
              "s",
              $purchase["id"]
            )->fetch_all(MYSQLI_ASSOC);
            foreach ($purchaseItems as $purchaseItem) {
            ?>
              <div class="order-product">
                <div class="oi-img">
                  <img src="<?php echo $purchaseItem["image"] ?>" alt="" srcset="" />
                </div>
                <a href="product.php?id=<?php echo $purchaseItem["id"] ?>" class="oi-name"><?php echo $purchaseItem["prodName"] ?> </a>
                <div class="oi-count">Amount: <?php echo $purchaseItem["amount"] ?></div>
                <div class="oi-price"><?php echo $purchaseItem["price"] ?>$</div>
                <div class="oi-price text-warning"><?php echo $purchaseItem["status"] ?></div>
              </div>
              <hr>
            <?php } ?>
          </div>
          <div class="order-total">Total of <?php echo $purchase["total"] ?>$</div>
          <div class="order-address">
            <div class="a-header">Shipping Address</div>
            <div class="a-info">
              <?php echo $purchase["address"] ?>
            </div>
          </div>
          <div class="order-address">
            <div class="a-header">Ordered At</div>
            <div class="a-info"><?php echo $purchase["createdAt"]; ?></div>
          </div>
        </div>
      <?php } ?>


    </div>
  </div>
</div>
</div>
<div class="container mx-auto"></div>

<style>
  .order-total {
    border-top: 1px solid #b6b6b6;
    margin: 10px 20px;
    text-align: center;
    padding: 10px;
    font-size: 24px;
    font-weight: bold;
  }

  .order-address {
    display: flex;
    border: 1px solid #b6b6b6;
    margin: 10px;
    border-radius: 20px;
    overflow: hidden;
  }

  .a-header {
    width: 150px;
    padding: 10px;

    background-color: rgb(235, 235, 235);
  }

  .a-info {
    padding: 10px;
  }

  .order-item {
    border: 1px solid #b6b6b6;
    border-radius: 20px;
    margin-top: 20px;
    background-color: #F8F7F7;
  }

  .order-product img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 20px;
    border: 1px solid #b6b6b6;
  }

  .oi-img {
    flex: 1;
  }

  .oi-price {
    flex: 1;
  }

  .oi-count {
    flex: 1;
  }

  .oi-name {
    font-weight: bold;
    flex: 1;
  }

  .order-product {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    text-wrap: nowrap;
    justify-content: space-between;
    padding: 10px 20px 10px 20px;
    font-size: 17px;
  }

  .p-wrapper {
    border: 1px solid rgb(189, 189, 189);
    border-radius: 15px;
    max-width: 700px;
    padding: 20px;
    margin-left: auto;
    margin-right: auto;
  }

  .p-header {
    margin-top: 20px;
    text-align: center;
  }

  .p-info {
    display: flex;
    border: 1px solid rgb(201, 201, 201);
    margin-bottom: 10px;
    margin-top: 10px;
    overflow: hidden;
    border-radius: 10px;
    flex-wrap: wrap;
  }

  .pi-content {
    padding: 10px;
  }

  .pi-name {
    width: 150px;
    overflow: hidden;
    font-weight: bold;
    background-color: rgb(235, 235, 235);
    border-right: 1px solid rgb(235, 235, 235);
    padding: 10px;
  }

  .profile-pic img {
    width: 100px;
    border-radius: 20px;
  }

  .picname {
    margin-top: 20px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
  }

  .profile-ns {
    display: flex;
    justify-content: space-around;
    gap: 10px;
    margin-left: 20px;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
  }

  .profile-surname {
    text-transform: uppercase;
  }

  .pi-content {
    border: none;
    outline: none;
  }

  .pi-content:focus {
    outline: none;
  }
</style>
<script>
  <?php if (count($messages) > 0) { ?>
    new Toast({
      message: "<?php foreach ($messages as $message) echo $message . " " ?>",
      type: "success"
    })
  <?php } ?>
</script>