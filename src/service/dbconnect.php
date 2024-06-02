<?php

if (!isset($mysqli)) {
    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "eticdot";
    $port = 3306;
    $mysqli = new mysqli($hostname, $username, null, $database, $port);
    $stmt_execute = function (string $sql, string $types, string ...$args) use ($mysqli) {
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$args);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    };
}
