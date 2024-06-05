<?php
include_once "service/user-auth-utils.php";
$userLogin = "login.php";

$loginInfo = $getUserLoginInfo();
$loginInfo->loggedIn;
if (!$loginInfo->loggedIn) {
    header("Location: " . $userLogin);
    exit();
}

$userId = $loginInfo->userId;
