<?php
include_once "service/dbconnect.php";
include_once "service/user-auth-utils.php";
include_once "service/utils.php";
$loginInfo = $getUserLoginInfo();
$loggedIn = $loginInfo->loggedIn;
$userId = $loginInfo->userId;
$is_favorite = false;
$canReview = false;
$errors = [];
if ($loggedIn) {
  $is_favorite = $stmt_execute(
    "select * from favorite where prodId = ? and userId = ?",
    "ss",
    $prodId,
    $userId
  )->num_rows > 0;
  $canReview = $loggedIn && $stmt_execute(
    "select * from review where prodId = ? and userId = ? ",
    "ss",
    $prodId,
    $userId
  )->num_rows < 1 &&
    $stmt_execute(
      "select * from purchase_item pi, purchase p, product pr
  where p.id = pi.purchaseId and pi.productId = pr.id and p.userId = ? and 
  pr.id = ?
  ",
      "ss",
      $userId,
      $prodId
    )->num_rows > 0;
}
if (
  $_SERVER["REQUEST_METHOD"] == "POST" &&
  $canReview
) {
  if (
    isblank_post("id", "rating") &&
    intval($_POST["rating"]) > 6 && intval($_POST['rating']) <= 0
  ) {
    array_push($errors, "Please enter rating.");
  } else {
    $title = isset($_POST["title"]) ? $_POST['title'] : '';
    $text = isset($_POST["text"]) ? $_POST['text'] : '';

    $stmt_execute(
      "insert into review values 
    (uuid(), ?, ?, ?, ?, ?)
    ",
      "ssdss",
      $title,
      $text,
      $_POST["rating"],
      $prodId,
      $userId
    );
  }
}
$product = $stmt_execute(
  "SELECT p.id,p.name as prodName, v.name as vendorName, p.image, p.description
  from product p, vendor v 
  where p.vendorId = v.id and p.id = ?",
  "s",
  $prodId
);
if ($product->num_rows < 1) {
  include_once "service/utils.php";
  redirect("index.php");
  exit;
}
$product = $product->fetch_assoc();
$rating = $stmt_execute(
  "SELECT coalesce(avg(rating),0) as rating from review
  where prodId = ?",
  "s",
  $prodId
)->fetch_assoc()["rating"];
$rating = floatval($rating);
$reviews = $stmt_execute(
  "SELECT  
  concat(u.name, ' ', u.surname) as namesurname,
  r.rating, r.title, r.text from
  product p, review r, user u
  where p.id = r.prodId and
  u.id = r.userId and r.prodId = ? 
   ",
  "s",
  $prodId
)->fetch_all(MYSQLI_ASSOC);


?>
<div class="container-product container mx-auto">
  <div class="frame-product">
    <div class="product-name">
      <?php echo $product["prodName"] ?>
      <span class="prod-vendor-name text-blue">
        <?php echo $product["vendorName"] ?>
      </span>
    </div>
    <div class="product-info">
      <img class="product-image" src="<?php echo $product['image'] ?>" width="200" alt="" srcset="" />

      <div class="product-details">
        <div class="details-hug">
          <div class="prod-rating text-blue">
            <?php for ($i = 0; $i <= 4; $i++) {
              if ($rating >= 1) echo "<i class='bi bi-star-fill'></i>";
              else if ($rating <= 0) echo "<i class='bi bi-star'></i>";
              else  echo "<i class='bi bi-star-half'></i>";
              $rating = $rating - 1;
            } ?>
          </div>
          <div class="prod-desc">
            <?php echo $product["description"] ?>
          </div>
          <div class="prod-actions">
            <button <?php if ($loggedIn) echo "data-prodid=" . $product["id"] ?> class="fav-btn">
              <?php if ($is_favorite) { ?>
                <i class="bi bi-heart-fill"></i>
              <?php } else { ?>
                <i class="bi bi-heart"></i>
              <?php } ?>
            </button>
            <button <?php if ($loggedIn) echo "data-prodid=" . $product["id"] ?> class="basket-btn bg-green">
              <i class="bi bi-basket2-fill"></i> Add To Basket
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container mx-auto mt-2 comments-container">
  <h3>Reviews</h3>
  <hr>
  <?php if ($canReview) { ?>
    <h3 class="mt-12">Add Review</h3>
    <form class="form-group" action="product.php" method="post">
      <div class="my-4 ">

        <label id="r1" for="rating1" class="rating text-blue">
          <i class='bi bi-star'></i>
        </label>
        <input value="1" type="radio" hidden name="rating" id="rating1">


        <label id="r2" for="rating2" class="rating text-blue">
          <i class='bi bi-star'></i>
        </label>
        <input value="2" type="radio" hidden name="rating" id="rating2">

        <label id="r3" for="rating3" class="rating text-blue">
          <i class='bi bi-star'></i>
        </label>
        <input value="3" type="radio" hidden name="rating" id="rating3">

        <label id="r4" for="rating4" class="rating text-blue">
          <i class='bi bi-star'></i>
        </label>
        <input value="4" type="radio" hidden name="rating" id="rating4">

        <label id="r5" for="rating5" class="rating text-blue">
          <i class='bi bi-star'></i>
        </label>
        <input value="5" type="radio" hidden name="rating" id="rating5">

      </div>
      <input hidden type="text" name="id" value="<?php echo $prodId ?>" id="">
      <label for="title">Title</label>
      <input class="form-control" type="text" name="title" id="title">
      <label class="mt-4" for="text">Review</label>
      <textarea class="form-control" name="text" id="text"></textarea>
      <button class="btn btn-primary mt-4" type="submit">Add Comment</button>

    </form>
  <?php } ?>
  <hr>
  <?php foreach ($reviews as $review) {
    $rating = floatval($review["rating"]);
  ?>
    <div class="comment">
      <div class="comment-profile">
        <img src="images/profile.png" class="comment-pic" alt="" srcset="" />
        <div class="comment-name"><?php echo $review["namesurname"] ?></div>
        <div class="comment-rating text-blue">
          <?php for ($i = 0; $i <= 4; $i++) {
            if ($rating >= 1) echo "<i class='bi bi-star-fill'></i>";
            else if ($rating <= 0) echo "<i class='bi bi-star'></i>";
            else  echo "<i class='bi bi-star-half'></i>";
            $rating = $rating - 1;
          } ?>
        </div>
      </div>
      <?php if (!empty($review["title"])) { ?>
        <div class="comment-content">
          <b><?php echo $review["title"] ?></b>
          <br>
          <?php echo $review["text"] ?>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</div>
<script>
  <?php if (count($errors) > 0) { ?>
    new Toast({
      message: "<?php foreach ($errors as $error) echo $error . " " ?>"

    })
  <?php } ?>
  for (let i = 1; i <= 5; i++) {
    $("#r" + i).click((e) => {
      for (let j = 1; j <= 5; j++) {
        $('#r' + j).html("<i class='bi bi-star'></i>")
      }
      for (let j = 1; j <= i; j++) {
        $('#r' + j).html("<i class='bi bi-star-fill'></i>")
      }
    })

  }
</script>
<style>
  .rating:hover {
    cursor: pointer;
  }

  .product-image {
    min-height: 200px;
    max-height: 400px;
    flex: 2;
    object-fit: contain;
  }

  .comment-rating {
    font-size: 20px;
  }

  .comment {
    margin-top: 15px;
    padding: 10px;
    border: 1px solid #989898;
    border-radius: 20px;
    background-color: #f9f9f9;
  }

  .comment-content {
    padding: 10px;
    font-size: 16px;
    font-weight: 300;
    background-color: #e2e2e2;
    margin-top: 8px;
    border-radius: 20px;
  }

  .comment-name {
    font-weight: bold;
    font-size: 16px;
  }

  .comment-profile {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .comment-pic {
    width: 50px;
    height: 50px;
    border-radius: 50%;
  }

  .prod-actions {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .frame-product {
    border: 1px solid #989898;
    border-radius: 20px;
    margin-top: 40px;
    padding: 40px 20px;
  }

  .product-name {
    font-size: 32px;
    text-align: center;
    font-weight: bolder;
    margin-bottom: 8px;
  }

  .prod-vendor-name {
    font-size: 24px;
    font-weight: bolder;
  }

  .product-info {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 20px;
  }

  .product-details {
    flex: 1;
  }

  .details-hug {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
  }

  .prod-desc {
    margin: 20px;
    width: 180px;
    font-size: 14px;
    text-align: center;
  }
</style>
</BaseLayout>