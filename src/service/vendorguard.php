<?php
include_once "service/dbconnect.php";
$vendorLogin = "vendor-login.php";

session_start();
if (
    !isset($_SESSION["vendorId"]) ||
    !isset($_SESSION["isLoggedIn"]) ||
    $stmt_execute("select * from vendor where id = ?", "s", $_SESSION["vendorId"])->num_rows < 1
) {
    header("Location: " . $vendorLogin);
    exit();
}

$vendorId = $_SESSION["vendorId"];
