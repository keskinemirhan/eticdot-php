<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "service/dbconnect.php";
    include "service/utils.php";
    include "service/user-auth-utils.php";

    $loginInfo = $getUserLoginInfo();
    $userId = $loginInfo->userId;
    if (!$loginInfo->loggedIn) {
        http_response_code(403);
    } else if (isblank_post("prodId")) {
        http_response_code(400);
    } else {
        $prodId = $_POST["prodId"];
        $productExists = $stmt_execute(
            "select * from product where id = ?",
            "s",
            $prodId
        )->num_rows > 0;
        $favoriteExists = $stmt_execute(
            "select * from favorite where userId = ? and prodId = ?",
            "ss",
            $userId,
            $prodId
        )->num_rows > 0;
        if ($favoriteExists) {
            $stmt_execute(
                "delete from favorite where userId = ? and prodId = ? ",
                "ss",
                $userId,
                $prodId
            );
            http_response_code(204);
        } else if (!$favoriteExists && $productExists) {
            $stmt_execute(
                "insert into favorite values (uuid(), ?, ?)",
                "ss",
                $userId,
                $prodId
            );
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }
} else
    http_response_code(404);
