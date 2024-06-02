<?php
$adminLogin = "admin-login.php";

session_start();
if (!isset($_SESSION["adminId"]) || !isset($_SESSION["isLoggedIn"])) {
    header("Location: " . $adminLogin);
    exit();
}

$adminId = $_SESSION["adminId"];
