<?php
include_once("service/user-auth-utils.php");
include_once("service/dbconnect.php");
$loginInfo = $getUserLoginInfo();

static $prod_card_script_exists = false;

$c_prod_card = function ($prod_id, $prod_name, $price, $prev_price, $vendor_name, $image_url, $is_favorite = false) use ($loginInfo, $stmt_execute) {
    $rating = $stmt_execute(
        "SELECT coalesce(avg(rating),0) as rating from review
        where prodId = ?",
        "s",
        $prod_id
    )->fetch_assoc()["rating"];
    $rating = floatval($rating);
    $loggedIn = $loginInfo->loggedIn;
    $item = [
        "price" =>  $price,
        "prevPrice" =>  $prev_price,
        "prodName" =>  $prod_name,
        "vendorName" =>  $vendor_name,
        "imageUrl" =>  $image_url,
        "prodId" =>  $prod_id,
    ];
?>

    <a href="product.php?id=<?php echo $prod_id ?>" class="prod-card">
        <div class="price-band bg-blue">
            <div class="price">
                <div class="current-price"><?php echo $price . "$" ?></div>
                <?php if ($prev_price != $price) { ?>
                    <div class="prev-price"><?php echo $prev_price . "$" ?></div>
                <?php } ?>
            </div>

            <?php if ($prev_price != $price) { ?>

                <div class="sale bg-green">
                    <?php
                    echo ceil((($prev_price - $price) / $prev_price) * 100) . "% OFF";
                    ?>
                </div>
            <?php } ?>
        </div>
        <div class="fav-btn-box">
            <button <?php if ($loggedIn) echo "data-prodId='$prod_id'" ?> class="fav-btn">
                <?php if ($is_favorite) { ?>
                    <i class="bi bi-heart-fill"></i>
                <?php } else { ?>
                    <i class="bi bi-heart"></i>
                <?php } ?>
            </button>
        </div>
        <div class="prod-img-wrapper">
            <img class="prod-img" src="<?php echo $image_url ?>" alt="" srcset="" />
        </div>
        <div class="prod-name-wrapper">
            <span class="vendor-name text-blue"><?php echo $vendor_name ?></span>
            <span class="prod-name"><?php echo $prod_name ?></span>
        </div>
        <div class="prod-rating text-blue">
            <?php for ($i = 0; $i <= 4; $i++) {
                if ($rating >= 1) echo "<i class='bi bi-star-fill'></i>";
                else if ($rating <= 0) echo "<i class='bi bi-star'></i>";
                else  echo "<i class='bi bi-star-half'></i>";
                $rating = $rating - 1;
            } ?>
        </div>
        <div class="basket-btn-wrapper">
            <button <?php if ($loggedIn) echo "data-prodid='$prod_id'" ?> class="basket-btn bg-green ">
                <i class="bi bi-basket2-fill"></i> Add To Basket
            </button>
        </div>
    </a>
<?php } ?>