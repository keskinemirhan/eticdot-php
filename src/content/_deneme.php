<!DOCTYPE html>
<html lang="en">

<head>
    <title>Deneme</title>
    <?php include "views/_headcontent.php" ?>

</head>


<body>
    <main style="min-height: 100vh;">
        <?php include "views/_navbar.php" ?>
        <?php
        include "views/_prodcard.php";
        c_prod_card("31", "muz", 31, 35, "Cikita", "google.com");
        ?>
    </main>


    <?php include "views/_footer.php" ?>

    <?php include "views/_bottomscript.php" ?>
</body>

</html>