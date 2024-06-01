<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eticdot - Login</title>
    <?php include "views/_headcontent.php" ?>

</head>

<body>
    <?php include "views/_navbar.php" ?>

    <main style="min-height: 100vh;">

        <div class="comp-c">
            <div class="comp-icon text-green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="comp-text text-green">Order Completed</div>
        </div>
        <style>
            .comp-c {
                margin-top: 30px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }

            .comp-icon {
                font-size: 80px;
            }

            .comp-text {
                font-size: 32px;
            }
        </style>

    </main>

    <?php include "views/_bottomscript.php" ?>
    <script>
        loadFavStatus();
        bindFavButton();
        updateNavBasket();
    </script>

    <?php include "views/_footer.php" ?>
</body>

</html>