<?php
$error = false;
$errorStrings = [];
$categories = $mysqli->query("select id , name from category");
$vendors = $mysqli->query("select id , name from vendor");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "service/dbconnect.php";
    if (
        empty($_POST["name"]) ||
        empty($_POST["categoryId"]) ||
        empty($_POST["vendorId"]) ||
        empty($_POST["price"]) ||
        empty($_POST["prevPrice"]) ||
        empty($_POST["desc"]) ||
        !isset($_FILES["image"])
    ) {
        $error = true;
        array_push($errorStrings, "Please Fill All Fields");
    } else {
        if (!$error) {
            $_FILES["image"]["name"] = strval(time()) . $_FILES["image"]["name"];
            $uploadDir = "images/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir);

            $image = $uploadDir;
            $name = $_POST["name"];
            $categoryId = $_POST["categoryId"];
            $vendorId = $_POST["vendorId"];
            $price = $_POST["price"];
            $prevPrice = $_POST["prevPrice"];
            $desc = $_POST["desc"];

            $stmtInsert = $mysqli->prepare("INSERT INTO product
             VALUES (uuid(),? ,?, ? ,? ,?, ?, ? )");
            $stmtInsert->bind_param(
                "sdsdsss",
                $name,
                $price,
                $desc,
                $prevPrice,
                $image,
                $categoryId,
                $vendorId,
            );
            $stmtInsert->execute();
        }
    }
}
?>
<h1>Add Product</h1>
<hr>

<form enctype="multipart/form-data" method="post" class="mx-auto" action="admin-product-add.php">

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
    <div class="mb-3">
        <label class="form-label" for="desc">Description: </label>
        <input class="form-control" type="text" name="desc" id="desc">
    </div>
    <div class="mb-3">
        <label class="form-label" for="price">Price: </label>
        <input class="form-control" type="number" , step="0.01" name="price" id="price">
    </div>
    <div class="mb-3">
        <label class="form-label" for="prevPrice">Previous Price: </label>
        <input class="form-control" type="number" , step="0.01" name="prevPrice" id="prevPrice">
    </div>

    <div class="mb-3">
        <label class="form-label" for="categoryId">Category: </label>
        <select class="form-control" name="categoryId" id="categoryId">
            <?php while ($category = $categories->fetch_row()) { ?>

                <option value="<?php echo $category[0] ?>">
                    <?php echo $category[1] ?>
                </option>
            <?php } ?>

        </select>
    </div>
    <div class="mb-3">
        <label class="form-label" for="vendorId">Vendor: </label>
        <select class="form-control" name="vendorId" id="vendorId">
            <?php while ($vendor = $vendors->fetch_row()) { ?>

                <option value="<?php echo $vendor[0] ?>">
                    <?php echo $vendor[1] ?>
                </option>
            <?php } ?>

        </select>
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
                message: "Product Successfully Added",
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