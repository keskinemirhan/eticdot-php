<?php

if (session_status() != PHP_SESSION_ACTIVE)
    session_start();
$loggedIn = false;
$basketCount = "";
if (isset($_SESSION["userId"]) && isset($_SESSION["userIsLoggedIn"])) {
    include "service/dbconnect.php";
    $userId = $_SESSION["userId"];
    $userQuery = $stmt_execute("select name from user where id = ?", "s", $userId);
    if ($userQuery->num_rows > 0) {
        $username = $userQuery->fetch_row()[0];
        $loggedIn = true;
    }
    $basketCount = $stmt_execute(
        "select sum(amount) as sum from basketProduct where userId = ? ",
        "s",
        $userId
    )->fetch_assoc()["sum"];
    $loggedIn = true;
}
?>
<nav>
    <a href="." class="nav-logo">
        <i class="bi bi-shop-window logo-icon text-blue"></i>
        <span class="logo-name d-none d-lg-block">Etic<span class="logo-dot text-blue">dot</span>.com</span>
    </a>
    <div class="nav-search d-none d-lg-block">
        <form method="get" action="search.php">
            <div class="bar-wrapper">
                <input type="text" class="nav-search-bar input-text" name="q" id="" />
            </div>
            <button class="nav-search-btn" type="submit">
                <i class="bi bi-search text-blue search-icon"></i>
            </button>
        </form>
    </div>
    <div class="nav-account d-none d-lg-flex">

        <?php if ($loggedIn) { ?>
            <a href="profile.php" class="nav-btn nav-profile">
                <i class="bi bi-person-circle user-icon text-green"></i>
                <span class="profile"><?php echo $username ?></span>
            </a>
        <?php } else { ?>
            <a href="login.php" class="nav-btn nav-profile">
                <i class="bi bi-person-circle user-icon text-blue"></i>
                <span class="profile">Login</span>
            </a>
        <?php } ?>
        <a href="basket.php" class="nav-btn nav-basket basket-icon">
            <i class="bi bi-basket2-fill text-blue"></i>
            <span> <span class="basket-count-nav"><?php echo $basketCount ?> </span> Items</span></a>
        <a href="favorites.php" class="nav-btn">
            <i class="bi bi-heart-fill text-blue"></i>
            <span>Favorites</span>
        </a>
    </div>
    <div class="nav-m-btn d-block d-lg-none text-blue">
        <i class="bi bi-list"></i>
    </div>
</nav>

<div class="nav-m-menu d-none d-lg-none">
    <div class="close-btn">
        <div class="btn"><i class="bi bi-x-lg text-blue"></i></div>
    </div>

    <div class="nav-search">
        <form method="get" action="search.php">
            <div class="bar-wrapper">
                <input type="text" class="nav-search-bar input-text" name="q" id="" />
            </div>
            <button class="nav-search-btn" type="submit">
                <i class="bi bi-search text-blue search-icon"></i>
            </button>
        </form>
    </div>
    <div class="nav-m-list">
        <?php if ($loggedIn) { ?>
            <a href="profile.php" class="nav-m-btn nav-profile">
                <i class="bi bi-person-circle user-icon text-green"></i>
                <span class="profile"><?php echo $username ?></span>
            </a>
        <?php } else { ?>
            <a href="login.php" class="nav-m-btn nav-profile">
                <i class="bi bi-person-circle user-icon text-blue"></i>
                <span class="profile">Login</span>
            </a>
        <?php } ?>
        <a href="basket.php" class="nav-m-btn">
            <i class="bi bi-basket2-fill text-blue"></i>
            <span> <span class="basket-count-nav"> <?php echo $basketCount ?></span> Items</span>
        </a>
        <a href="favorites.php" class="nav-m-btn">
            <i class="bi bi-heart-fill text-blue"></i>
            <span>Favorites</span>
        </a>
    </div>

    <script>
        const profileIndicators = document.querySelectorAll(".profile");
        const profileIcons = document.querySelectorAll(".user-icon");
        const profileLinks = document.querySelectorAll(".nav-profile");
        const user = JSON.parse(localStorage.getItem("logUser"));
        const mButton = document.querySelector(".nav-m-btn");
        const mClose = document.querySelector(".close-btn");
        const mMenu = document.querySelector(".nav-m-menu");
        mButton?.addEventListener("click", () => {
            mMenu?.classList.toggle("d-none");
        });
        mClose?.addEventListener("click", () => {
            mMenu?.classList.toggle("d-none");
        });
    </script>
    <style>
        .nav-m-btn i {
            animation-duration: 0.3s;
        }

        .nav-m-list {
            margin-top: 20px;
        }

        .nav-m-btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav-m-btn span {
            font-size: 20px;
            font-weight: bold;
            margin-left: 20px;
        }

        .close-btn i {
            font-size: 35px;
        }

        .nav-m-menu {
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.932);
            position: absolute;
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 10;
        }

        .nav-m-btn {
            font-size: 45px;
        }

        .nav-m-btn:hover {
            cursor: pointer;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 10px;
            padding-right: 10px;
            box-shadow:
                rgba(0, 0, 0, 0.16) 0px 3px 6px,
                rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        .nav-logo {
            display: flex;
            justify-items: center;
            align-items: center;
            flex: 2;
        }

        .logo-name {
            font-weight: bold;
            font-size: 36px;
            margin-left: 10px;
        }

        .logo-icon {
            font-size: 3rem;
        }

        .nav-search {
            flex: 3;
            margin: 0 10px 0 10px;
        }

        .nav-search form {
            justify-content: center;
        }

        .nav-account {
            flex: 2;
        }

        .nav-search form {
            display: flex;
        }

        .bar-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-width: 100px;
            max-width: 500px;
        }

        .nav-search-bar {
            width: 100%;
            height: 40px;
            border-radius: 40px;
        }

        .nav-search-btn {
            font-size: 25px;
            background-color: white;
            text-align: center;
            border: 0;
            margin-left: 10px;
        }

        .nav-account {
            display: flex;
            justify-content: end;
            margin-right: 20px;
        }

        .nav-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px;
            font-weight: bolder;
            margin-left: 5px;
        }

        .nav-btn i {
            font-size: 40px;
            height: 40px;
            margin-bottom: 5px;
        }

        .nav-btn span {
            font-size: 16px;
        }
    </style>
</div>