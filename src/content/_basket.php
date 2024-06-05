<?php
include_once "service/dbconnect.php";

$basketItems = $stmt_execute(
    "select p.id, p.image,p.name, v.name as vendorName, p.price, p.prevPrice, b.amount
     from product p, vendor v, basketProduct b 
     where p.id = b.prodId and v.id = p.vendorId and b.userId = ?",
    "s",
    $userId
);
$total = $stmt_execute(
    "select sum(b.amount * p.price) as total 
    from basketProduct b, product p
     where p.id = b.prodId and userId = ? ",
    "s",
    $userId
)->fetch_assoc()["total"];


function basketProductData($prodId, $prodName, $image, $vendorName, $price, $prevPrice, $amount)
{
?>
    <div id="id<?php echo $prodId ?>" class="b-item-container container mx-auto mt-3 ">
        <div class="basket-item">
            <div class="img-name">
                <div class="basket-image" style="background-image:url('<?php echo $image ?>'); "></div>
                <a href="/product/<?php echo $prodId ?>" class="prod-name">
                    <span class="prod-vendor text-blue"><?php echo $vendorName ?> </span>
                    <?php echo $prodName ?>
                </a>
                <div class="prod-price text-blue"><?php echo $price ?>$
                    <?php if ($prevPrice != $price) { ?>
                        <span class="prev-price">
                            <?php echo $prevPrice ?>
                        </span>
                    <?php } ?>
                </div>
            </div>
            <div class="quantity">
                <button data-prodid="<?php echo $prodId ?>" class="prod-plus text-green"><i class="bi bi-plus-circle-fill"></i></button>
                <div class=" prod-quantity"><?php echo $amount ?></div>
                <button data-prodid="<?php echo $prodId ?>" class="prod-minus text-red"><i class="bi bi-dash-circle-fill"></i></button>
                <button data-prodid="<?php echo $prodId ?>" class="prod-trash text-red"><i class="bi bi-trash-fill"></i></button>
            </div>
        </div>
    </div>
<?php
}
?>
<div class="c-bt">Basket</div>
<div class="basket-items container mx-auto"></div>
<?php while ($item = $basketItems->fetch_assoc()) {
    basketProductData(
        $item["id"],
        $item["name"],
        $item["image"],
        $item["vendorName"],
        $item["price"],
        $item["prevPrice"],
        $item["amount"]
    );
} ?>
<?php if ($basketItems->num_rows < 1) { ?>
    <div class="empty-basket">
        <div class="text-dark-grey">No items...</div>
    </div>
<?php } else { ?>
    <div class="total-container">
        <div class="total-basket">
            <div class="total-price">
                <i class="bi bi-receipt text-blue"></i>
                <div class="m-total-price"><?php echo $total ?>$</div>
            </div>
            <a href="order.php" class="confirm-basket bg-green">Confirm Basket <i class="bi bi-check-circle-fill"></i></a>
        </div>
    </div>
<?php } ?>

<script>
</script>