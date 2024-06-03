<?php
include_once("service/user-auth-utils.php");
$loginInfo = $getUserLoginInfo();

static $prod_card_script_exists = false;

$c_prod_card = function ($prod_id, $prod_name, $price, $prev_price, $vendor_name, $image_url, $is_favorite = false) use ($loginInfo) {
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

    <a href="/product/<?php echo $prod_id ?>" class="prod-card">
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
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
            <i class="bi bi-star"></i>
        </div>
        <div class="basket-btn-wrapper">
            <button data-prodId="<?php echo htmlspecialchars($item["prodId"]) ?>" class="<?php echo "basket-btn bg-green basket-btn-$prod_id" ?> ">
                <i class="bi bi-basket2-fill"></i> Add To Basket
            </button>
        </div>
    </a>
<?php } ?>