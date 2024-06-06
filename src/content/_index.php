<?php
include "service/user-auth-utils.php";
include "service/dbconnect.php";
include "views/_prodcard.php";
include_once "views/_categorycard.php";
$loginInfo = $getUserLoginInfo();
$loggedIn = $loginInfo->loggedIn;
$userId = $loginInfo->userId;
$products;
$categories = $mysqli->query("select name, image from category");
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
    vendor v ON p.vendorId = v.id LIMIT ?;
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
    vendor v where p.vendorId = v.id LIMIT ?;",
        "i",
        9
    );
}
?>


<div class="container mx-auto">
    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <a href="#sale" class="carousel-item active" data-bs-interval="1000">
                <img src="images/sale1.jpg" class="d-block w-100" alt="..." />
            </a>
            <a href="search.php?q=electronics" class="carousel-item" data-bs-interval="2000">
                <img src="images/sale2.jpg" class="d-block w-100" alt="..." />
            </a>
            <a href="search.php?q=home" class="carousel-item">
                <img src="images/sale3.jpg" class="d-block w-100" alt="..." />
            </a>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="items container mx-auto">
        <div class="items-title">
            <span class="text-blue">Popular</span> Categories <span class="text-blue"><i class="bi bi-tags"></i></span>
        </div>
    </div>
    <div class="item-rack">
        <?php
        while ($category = $categories->fetch_assoc()) {
            categoryCard(
                $category["name"],
                $category["image"]
            );
        }

        ?>

    </div>

    <div class="items container mx-auto">
        <div class="items-title">
            <span class="text-blue">Popular</span> Products <span class="text-blue"><i class="bi bi-arrow-up-right"></i></span>
        </div>
        <div class="item-rack">
            <?php
            $pc = 0;
            while (($product = $products->fetch_assoc()) && $pc < 4) {
                $c_prod_card(
                    $product["id"],
                    $product["name"],
                    $product["price"],
                    $product["prevPrice"],
                    $product["vendorName"],
                    $product["image"],
                    $product["isFavorite"] == "1"
                );
                $pc++;
            }

            ?>
        </div>
    </div>
    <div class="items container mx-auto">
        <div class="items-title">
            <span id="sale" class="text-blue">Sale</span> Products <span class="text-blue"><i class="bi bi-tags"></i></span>
        </div>
        <div class="item-rack">
            <?php

            while (($product = $products->fetch_assoc()) && $pc < 10) {
                $c_prod_card(
                    $product["id"],
                    $product["name"],
                    $product["price"],
                    $product["prevPrice"],
                    $product["vendorName"],
                    $product["image"],
                    $product["isFavorite"] == "1"
                );
                $pc++;
            }

            ?>

        </div>
    </div>
</div>
</main>
<style>
    .car-con {
        width: 100%;
    }

    .car-con img {
        width: 100%;
    }

    .carousel {
        margin-top: 20px;
        border-radius: 20px;
        padding: 10px;
        box-shadow:
            #6166ff 0px 19px 38px,
            rgba(0, 0, 0, 0.22) 0px 15px 12px;
    }

    .carousel img {
        object-fit: contain;
        height: 550px;
    }

    @media (max-width: 1200px) {
        .carousel img {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .carousel img {
            height: 200px;
        }
    }

    .center img {
        margin: 120px;
    }

    .categories {
        margin-top: 50px;
    }

    .category-rack {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .items {
        margin-top: 40px;
    }

    .items-title {
        font-size: 32px;
        font-weight: bold;
    }

    .item-rack {
        background-color: #f2f2f2;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .banner-1 img {
        width: 100%;
    }

    .banner-2 img {
        width: 50%;
        margin: 10px;
    }

    .banner-2 {
        display: flex;
    }

    .banners {
        margin-top: 20px;
    }
</style>