<?php

if (!isset($_REQUEST["id"])) {
    header("Location: admin-category-list.php");
}
$title = "Home";
$childView = "content/_admin-category-update.php";
include "layout/admin-layout.php";
