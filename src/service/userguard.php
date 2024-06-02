<?php
$adminLogin = "login.php";

session_start();
if (!isset($_SESSION["userId"]) || !isset($_SESSION["isLoggedIn"])) {
    header("Location: " . $userLogin);
    exit();
}

$userId = $_SESSION["userId"];
