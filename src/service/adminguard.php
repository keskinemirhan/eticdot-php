<?php
include_once "service/dbconnect.php";
$adminLogin = "admin-login.php";

session_start();
if (
    !isset($_SESSION["adminId"]) ||
    !isset($_SESSION["isLoggedIn"]) ||
    $stmt_execute("select * from admin where id = ?", "s", $_SESSION["adminId"])->num_rows < 1
) {
    header("Location: " . $adminLogin);
    exit();
}

$adminId = $_SESSION["adminId"];
