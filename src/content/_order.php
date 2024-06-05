<?php

include_once "service/user-auth-utils.php";
include_once "service/utils.php";
$userId = $getUserLoginInfo()->userId;
$address = $stmt_execute(
    "select address from user where id = ?",
    "s",
    $userId
)->fetch_assoc()["address"];
$total = $stmt_execute(
    "select sum(b.amount * p.price) as total from product p, basketProduct b
    where p.id = b.prodId and b.userId = ?
     ",
    "s",
    $userId
)->fetch_assoc()["total"];
if ($total == 0) redirect(".");
$cardholder = "";
$cardnumber = "";
$expiremonth = "";
$expireyear = "";
$cvc = "";
$country = "";
$city = "";
$postalcode = "";


$errors = [];
$fieldErrors = [
    "cardholder" => false,
    "cardnumber" => false,
    "expiremonth" => false,
    "expireyear" => false,
    "cvc" => false,
    "address" => false,
    "country" => false,
    "city" => false,
    "postalcode" => false
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "service/dbconnect.php";

    if (isblank_post("cardholder", "cardnumber", "expiremonth", "expireyear")) {
        $fieldErrors["cardholder"] = true;
        $fieldErrors["cardnumber"] = true;
        $fieldErrors["expiremonth"] = true;
        $fieldErrors["expireyear"] = true;
        $fieldErrors["cvc"] = true;
        array_push($errors, "Please enter all card fields.");
    }
    if (isblank_post("address")) {
        $fieldErrors["address"] = true;
        array_push($errors, "Please fill address field");
    } else $address = $_POST["address"];
    if (isblank_post("country")) {
        $fieldErrors["country"] = true;
        array_push($errors, "Please fill country field");
    } else $country = $_POST["country"];
    if (isblank_post("city")) {
        $fieldErrors["city"] = true;
        array_push($errors, "Please fill city field");
    } else $city = $_POST["city"];

    if (isblank_post("postalcode")) {
        $fieldErrors["postalcode"] = true;
        array_push($errors, "Please fill postal code field");
    } else $postalcode = $_POST["postalcode"];
    if (count($errors) < 1) {
        $address = $_POST["country"] . "/" .
            $_POST["city"] . " " .
            $_POST["address"] . " " .
            $_POST["postalcode"];

        $total = $stmt_execute(
            "select sum(b.amount*p.price) as total 
         from basketProduct b, product p where p.id = b.prodId and userId = ?",
            "s",
            $userId
        )->fetch_assoc()["total"];

        $stmt_execute(
            "insert into purchase (id, userId, status, address,total) values 
            (uuid(), ?, ?, ?, ?)",
            "sssd",
            $userId,
            "Ordered",
            $address,
            $total
        );
        echo $userId;
        $purchaseId = $stmt_execute(
            "select id from purchase where userId = ? 
             order by createdAt desc LIMIT 1",
            "s",
            $userId
        )->fetch_assoc()["id"];

        $basketItems = $stmt_execute(
            "select p.id as prodId,
            p.price as price, 
            b.amount as amount from 
            product p, basketProduct b, user u
            where b.prodId = p.id and b.userId = u.id and u.id = ?",
            "s",
            $userId
        )->fetch_all(MYSQLI_ASSOC);

        foreach ($basketItems as $basketItem) {
            $stmt_execute(
                "insert into purchase_item
                (id, productId, priceSnapshot, amount, purchaseId, status)
                 values 
                (uuid(), ?, ?, ?, ?, ?)",
                "sdiss",
                $basketItem["prodId"],
                $basketItem["price"],
                $basketItem["amount"],
                $purchaseId,
                "Ordered"
            );
        }
        $stmt_execute(
            "delete from basketProduct where userId = ?",
            "s",
            $userId
        );
        redirect("success.php");
    }
} ?>
<form action="order.php" method="post">
    <div class="order-card-c container mx-auto">
        <div class="order-card mx-auto">
            <div class="order-card-title">
                <i class="bi bi-credit-card-2-front-fill text-blue"></i>
                Payment Card
            </div>
            <div class="card-form">
                <div class="card-holder card-big">
                    <label for="card-holder">Card Holder</label>
                    <input id="#card-holder" value="<?php echo $cardholder ?>" name="cardholder" class="auth-input card-wall <?php if ($fieldErrors["cardholder"]) echo "border-red"; ?>" type="text" />
                </div>
                <div class="card-number card-big">
                    <label for="card-number">Card Number</label>
                    <input id="#card-number" value="<?php echo $cardnumber ?>" name="cardnumber" class="auth-input card-wall <?php if ($fieldErrors["cardnumber"]) echo "border-red" ?>" type="text" />
                </div>

                <div class="card-info">
                    <div class="card-info-wr">
                        <label for="card-year">Expire Year</label>
                        <input id="#card-year" value="<?php echo $expireyear ?>" name="expireyear" class="auth-input card-piece <?php if ($fieldErrors["expireyear"]) echo "border-red" ?>" type="text" />
                    </div>
                    <div class="card-info-wr">
                        <label for="card-month">Expire Month</label>
                        <input id="#card-month" value="<?php echo $expiremonth ?>" name="expiremonth" class="auth-input card-piece <?php if ($fieldErrors["expiremonth"]) echo "border-red" ?>" type="text" />
                    </div>
                    <div class="card-info-wr">
                        <label for="card-cvc">CVC</label>
                        <input id="#card-cvc" value="<?php echo $cvc ?>" name="cvc" class="auth-input card-piece <?php if ($fieldErrors["cvc"]) echo "border-red" ?>" type="text" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="order-card-c container mx-auto">
        <div class="order-card mx-auto">
            <div class="order-card-title">
                <i class="bi bi-truck text-blue"></i>
                Address Info
            </div>
            <div class="card-form">
                <div class="card-holder card-big">
                    <label for="card-holder">Address</label>
                    <input id="#card-holder" value="<?php echo $address ?>" name="address" class="auth-input card-wall <?php if ($fieldErrors["address"]) echo "border-red" ?>" type="text" />
                </div>
                <div class="card-info">
                    <div class="card-info-wr">
                        <label for="card-year">Country</label>
                        <input id="#card-year" value="<?php echo $country ?>" name="country" class="auth-input card-piece <?php if ($fieldErrors["country"]) echo "border-red" ?>" type="text" />
                    </div>
                    <div class="card-info-wr">
                        <label for="card-month">City</label>
                        <input id="#card-month" value="<?php echo $city ?>" name="city" class="auth-input card-piece <?php if ($fieldErrors["city"]) echo "border-red" ?>" type="text" />
                    </div>
                    <div class="card-info-wr">
                        <label for="card-cvc">Postal Code</label>
                        <input id="#card-cvc" value="<?php echo $postalcode ?>" name="postalcode" class="auth-input card-piece <?php if ($fieldErrors["postalcode"]) echo "border-red" ?>" type="text" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="total-container">
        <div class="total-basket">
            <div class="total-price">
                <i class="bi bi-receipt text-blue"></i>
                <div class="m-total-price"><?php echo $total ?>$</div>
            </div>
            <button type="submit" class="confirm-order bg-green">Confirm Order <i class="bi bi-check-circle-fill"></i></button>
        </div>
    </div>
</form>
<style>
    .total-container {
        margin-top: 20px;
    }

    .card-info-wr {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .card-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .order-card {
        margin-top: 20px;
        max-width: 700px;
        border: 2px solid grey;
        padding: 10px;
        border-radius: 20px;
    }

    .card-wall {
        flex: 1;
        min-width: 200px;
    }

    .card-piece {
        width: 120px;
    }

    .card-big {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    label {
        font-weight: bold;
    }

    .order-card-title {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<script>
    <?php if (count($errors) > 0) { ?>
        new Toast({
            message: "<?php foreach ($errors as $error) echo $error . " " ?> ",
            type: "danger"
        });
    <?php } ?>
</script>