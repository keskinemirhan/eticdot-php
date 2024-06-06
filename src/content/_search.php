<?php
include "service/user-auth-utils.php";
include "service/dbconnect.php";
include "views/_prodcard.php";
include_once "views/_categorycard.php";
if (!isset($_GET["q"])) {
    redirect("index.php");
    exit;
}
$q = $_GET["q"];
$loginInfo = $getUserLoginInfo();
$loggedIn = $loginInfo->loggedIn;
$userId = $loginInfo->userId;
$products;
if ($loggedIn) {
    $products = $stmt_execute(
        "SELECT 
    p.id,
    p.name,
    p.price,
    p.prevPrice,
    p.image,
    p.categoryId,
    p.vendorId,
    v.name AS vendorName,
    CASE
        WHEN f.id IS NOT NULL THEN TRUE
        ELSE FALSE
    END AS isFavorite
FROM 
    product p
LEFT JOIN 
    favorite f ON p.id = f.prodId AND f.userId = ? 
JOIN 
    vendor v ON p.vendorId = v.id JOIN category c on p.categoryId = c.id and 
    (
        v.name like '%$q%' or
        p.name like '%$q%' or
        c.name like '%$q%' 
      ) LIMIT ?;
        ",
        "si",
        $userId,
        9
    );
} else {
    $products = $stmt_execute(
        "SELECT 
    p.id,
    p.name,
    p.price,
    p.prevPrice,
    p.image,
    p.categoryId,
    p.vendorId,
    v.name AS vendorName,
    0 as isFavorite
FROM 
    product p,
    vendor v , category c where p.vendorId = v.id and
    c.id = p.categoryId and (
        v.name like '%$q%' or
        p.name like '%$q%' or
        c.name like '%$q%' 
      )
    LIMIT ?;",
        "i",
        9
    );
}
?>

<div class="search-title">Search Results...</div>
<div class="container mx-auto">
    <div class="result-container">
        <?php
        while (($product = $products->fetch_assoc())) {
            $c_prod_card(
                $product["id"],
                $product["name"],
                $product["price"],
                $product["prevPrice"],
                $product["vendorName"],
                $product["image"],
                $product["isFavorite"] == "1"
            );
        }
        ?>
    </div>
</div>
<style is:inline>
    .search-title {
        font-size: 32px;
        text-align: center;
        margin-top: 30px;
        font-weight: bold;
    }

    .result-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        background-color: #f2f2f2;

        padding: 20px;
        border-radius: 20px;
        margin-top: 30px;
    }
</style>