<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <?php include "views/_headcontent.php" ?>

</head>

<body>
    <div id="loadOverlay" style="background-color:#ffffff; position:absolute; top:0px; left:0px; width:100%; height:100%; z-index:2000;"></div>
    <?php include "views/_navbar.php" ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="script/toast.js"></script>
    <main style="min-height: 100vh;">
        <div class="main-container container">
            <?php include($childView) ?>
        </div>
    </main>
    <script src="script/script.js"></script>
    <style>
        .main-container {
            padding: 20px;

        }

        #loadOverlay {
            display: none;
        }
    </style>

    <?php include "views/_footer.php" ?>

</body>

</html>