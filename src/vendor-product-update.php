<?php

if (!isset($_REQUEST["id"])) {
    header("Location: vendor-product-list.php");
}
$title = "Product Update";
$childView = "content/_vendor-product-update.php";
include "layout/vendor-layout.php";
