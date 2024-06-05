<?php
include_once "service/dbconnect.php";
class UserLoginInfo
{
    public bool $loggedIn;
    public string $userId;
    public function __construct(bool $loggedIn, string $userId)
    {
        $this->userId = $userId;
        $this->loggedIn = $loggedIn;
    }
}

$getUserLoginInfo = function () use ($stmt_execute) {
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (
        isset($_SESSION["userId"]) &&
        !empty($_SESSION["userId"]) &&
        isset($_SESSION["userIsLoggedIn"]) &&
        $_SESSION["userIsLoggedIn"] == true &&
        $stmt_execute("select * from user where id = ?", "s", $_SESSION["userId"])->num_rows > 0
    ) {
        return new UserLoginInfo(true, $_SESSION["userId"]);
    }
    return new UserLoginInfo(false, "");
};
