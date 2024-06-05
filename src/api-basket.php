<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "service/dbconnect.php";
    include "service/utils.php";
    include "service/user-auth-utils.php";

    $loginInfo = $getUserLoginInfo();
    $userId = $loginInfo->userId;
    if (!$loginInfo->loggedIn) {
        http_response_code(403);
        exit;
    }
    if (
        isblank_post("prodId") ||
        !(isset($_POST["delete"]) xor isset($_POST["add"]))
    ) {
        http_response_code(400);
        exit;
    }
    $prodId = $_POST["prodId"];
    $productExists = $stmt_execute(
        "SELECT * FROM product WHERE id = ? ",
        "s",
        $prodId
    )->num_rows > 0;

    if (!$productExists) {
        http_response_code(404);
        exit;
    }
    if (isset($_POST["add"])) {
        $basketProductResult = $stmt_execute(
            "SELECT id FROM basketProduct WHERE prodId = ? AND userId = ?",
            "ss",
            $prodId,
            $userId
        );
        if ($basketProductResult->num_rows > 0) {
            $basketProductId = $basketProductResult->fetch_assoc()["id"];
            $stmt_execute(
                "update basketProduct set amount = amount + 1 where id = ?",
                "s",
                $basketProductId
            );
            http_response_code(200);
        } else {
            $stmt_execute(
                "insert into basketProduct values (uuid(), ?, ?, 1)",
                "ss",
                $userId,
                $prodId
            );
            http_response_code(200);
        }
    } else {
        $basketProductResult = $stmt_execute(
            "select id, amount from basketProduct where userId = ? and prodId = ?",
            "ss",
            $userId,
            $prodId
        );
        if ($basketProductResult->num_rows < 1) {
            http_response_code(404);
            exit;
        }
        $basketProduct = $basketProductResult->fetch_assoc();
        if (isset($_POST["whole"])) {
            $stmt_execute(
                "delete from basketProduct where id = ?",
                "s",
                $basketProduct["id"]
            );
        } else {
            if (intval($basketProduct["amount"]) < 2) {
                $stmt_execute(
                    "delete from basketProduct where id = ? ",
                    "s",
                    $basketProduct["id"]
                );
            } else {
                $stmt_execute(
                    "update basketProduct set amount = amount - 1 where id = ?",
                    "s",
                    $basketProduct["id"]
                );
            }
            http_response_code(200);
        }
    }
    $count = $stmt_execute(
        "select coalesce(sum(amount),0) as sum from basketProduct where userId = ?",
        "s",
        $userId
    )->fetch_assoc()["sum"];
    $total = $stmt_execute(
        "select coalesce(sum(b.amount * p.price),0) as total 
        from basketProduct b, product p
         where p.id = b.prodId and userId = ? ",
        "s",
        $userId
    )->fetch_assoc()["total"];
    $prodAmount = $stmt_execute(
        "select coalesce(sum(amount),0) as sum from basketProduct where prodId = ? and userId = ? ",
        "ss",
        $prodId,
        $userId
    )->fetch_assoc()["sum"];


    $response = [
        "count" => intval($count),
        "total" => floatval($total),
        "prodAmount" => intval($prodAmount)

    ];
    echo json_encode($response);
} else

    http_response_code(404);
