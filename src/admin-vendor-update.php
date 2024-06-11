<?php

if (!isset($_REQUEST["id"])) {
    header("Location: admin-vendor-list.php");
}
$title = "Vendor Update";
$childView = "content/_admin-vendor-update.php";
include "layout/admin-layout.php";
