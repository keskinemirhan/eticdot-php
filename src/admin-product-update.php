<?php

if (!isset($_REQUEST["id"])) {
    header("Location: admin-product-list.php");
}
$title = "Product Update";
$childView = "content/_admin-product-update.php";
include "layout/admin-layout.php";
