<?php
include "service/dbconnect.php";
$adminLogin = "login.php";

session_start();
if (
    !isset($_SESSION["userId"]) ||
    !isset($_SESSION["userIsLoggedIn"]) ||
    $stmt_execute("select * from user where id = ?", "s", $_SESSION["userId"])->num_rows < 1
) {
    header("Location: " . $userLogin);
    exit();
}

$userId = $_SESSION["userId"];
