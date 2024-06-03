<?php
$error = false;
$errorStrings = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "service/dbconnect.php";
    if (!isset($_POST["name"]) || empty($_POST["name"]) || !isset($_FILES["image"])) {
        $error = true;
        echo $_FILES["image"];
        array_push($errorStrings, "Please Fill All Fields");
    } else {
        $_FILES["image"]["name"] = strval(time()) . $_FILES["image"]["name"];
        $uploadDir = "images/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir);
        $image = $uploadDir;
        $name = $_POST["name"];

        $stmtNameControl = $mysqli->prepare("SELECT * FROM category WHERE name = ?");
        $stmtNameControl->bind_param("s", $name);
        $stmtNameControl->execute();
        $nameControl = $stmtNameControl->get_result();
        $stmtNameControl->close();
        if ($nameControl->num_rows > 0) {
            $error = true;
            array_push($errorStrings, "Given name already exists");
        }
        if (!$error) {
            $stmtInsert = $mysqli->prepare("INSERT INTO category VALUES (uuid(), ?, ? )");
            $stmtInsert->bind_param("ss", $name, $image);
            $stmtInsert->execute();
        }
    }
}
?>
<h1>Add Category</h1>
<hr>

<form enctype="multipart/form-data" method="post" class="mx-auto" action="admin-category-add.php">

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") foreach ($errorStrings as $str) { ?>
        <div class="alert alert-warning"><?php echo $str ?></div>
    <?php } ?>
    <div class="mb-3">
        <label class="form-label" for="image">Image: </label>
        <input class="form-control" type="file" name="image" id="image">
    </div>
    <div class="mb-3">
        <label class="form-label" for="name">Name: </label>
        <input class="form-control" type="name" name="name" id="name">
    </div>
    <input class="btn btn-success form-submit" type="submit" value="Add">
</form>
<style>
    form {
        width: 500px;
    }
</style>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
    <script>
        <?php if (!$error) { ?>
            new Toast({
                message: "Category Successfully Added",
                type: "success",
            });
        <?php } else { ?>
            new Toast({
                message: "Error in form",
                type: "danger",
            });
        <?php } ?>
    </script>
<?php } ?>