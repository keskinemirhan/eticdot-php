<?php
$prodId;
if ($_SERVER["REQUEST_METHOD"] == "GET" && (!isset($_GET["id"]) || empty($_GET["id"]))) {
    header("Location: index.php");
    exit;
} else {
    $prodId = $_REQUEST["id"];
}
$title = "Product";
$childView = "content/_product.php";

include "layout/site-layout.php";
