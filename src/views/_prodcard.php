<?php /*
const { price, prevPrice, prodName, prodId, vendorName, imageUrl } =
Astro.props;
const item = {
price,
prevPrice,
prodName,
vendorName,
imageUrl,
prodId,
};
*/
?>
<?php
function c_prod_card($prod_id, $prod_name, $price, $prev_price, $vendor_name, $image_url)
{
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
                <?php if ($prev_price) { ?>
                    <div class="prev-price"><?php echo $prev_price . "$" ?></div>
                <?php } ?>
            </div>

            <?php if ($prev_price) { ?>

                <div class="sale bg-green">
                    <?php
                    echo ceil((($prev_price - $price) / $prev_price) * 100) . "% OFF";
                    ?>
                </div>
            <?php } ?>
        </div>
        <div class="fav-btn-box">
            <button data-item="<?php echo htmlspecialchars(json_encode($item)) ?>" class="<?php echo "fav-btn fav-btn-$prod_id" ?>"><i class="bi bi-heart"></i></button>
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
            <button data-item="<?php echo htmlspecialchars(json_encode($item)) ?>" class="<?php echo "basket-btn bg-green basket-btn-$prod_id" ?> ">
                <i class="bi bi-basket2-fill"></i> Add To Basket
            </button>
        </div>
    </a>
    <script>
        // basket
        const item = {
            price: <?php echo $price ?>,
            prevPrice: <?php echo $prev_price ?>,
            prodName: "<?php echo $prod_name ?>",
            vendorName: "<?php echo $vendor_name ?>",
            imageUrl: "<?php echo $image_url ?>",
            prodId: "<?php echo $prod_id ?>",
        };
        const basketButton = document.querySelector(`.basket-btn-${item.prodId}`);
        basketButton.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            let basketItems = JSON.parse(localStorage.getItem("basketItems"));
            if (!basketItems) basketItems = [];
            const existingIndex = basketItems.findIndex(
                (itm) => itm.prodId === item.prodId
            );
            if (existingIndex !== -1) {
                basketItems[existingIndex].count++;
            } else {
                basketItems.push({
                    ...item,
                    count: 1
                });
            }
            localStorage.setItem("basketItems", JSON.stringify(basketItems));
            new Toast({
                message: "Added to Basket",
                type: "success",
            });
            updateNavBasket();
        });
    </script>
<?php } ?>