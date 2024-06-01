<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <?php include "views/_headcontent.php" ?>

</head>

<body>

    <?php include "views/_navbar.php" ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="script/toast.js"></script>
    <script src="script/script.js"></script>
    <main style="min-height: 100vh;">
        <?php include($childView) ?>
    </main>
    <script>
        basketBtnEvent();
        loadFavStatus();
        bindFavButton();
        updateNavBasket();
    </script>

    <?php include "views/_footer.php" ?>

</body>

</html>