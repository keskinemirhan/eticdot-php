<?php
if (!isset($_REQUEST["id"])) {
    http_response_code(404);
    exit();
}
$id = $_REQUEST["id"];
$stmt = $mysqli->prepare("SELECT 
p.id,p.image, p.name, v.id, c.id ,p.price, p.prevPrice, p.description
from category as c, product as p, vendor as v
where c.id = p.categoryId and v.id = p.vendorId and p.id = ? and p.vendorId = ?");

$stmt->bind_param("ss", $id, $vendorId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows < 1) {
    http_response_code(404);
    echo "<h1 style='text-align: center;'>Not found...</h1>";
    exit();
}
$product = $result->fetch_row();
$stmt->close();
$categories = $mysqli->query("select id , name from category");
$errorStrings = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["delete"])) {
    $deleteStmt = $mysqli->prepare("delete from product where id = ?");
    $deleteStmt->bind_param("s", $id);
    $deleteStmt->execute();
    $deleteStmt->close();
    unlink($product[1]);
    array_push($errorStrings, "Removed...");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_REQUEST["delete"])) {
    $error = false;
    if (
        empty($_POST["name"]) ||
        empty($_POST["price"]) ||
        empty($_POST["prevPrice"]) ||
        empty($_POST["desc"]) ||
        empty($_POST["categoryId"])
    ) {
        $error = true;
        array_push($errorStrings, "Please Fill All Fields");
    }
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["name"])) {
        unlink($product[1]);
        $uploadDir = "images/" . strval(time()) . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir);
        $imageUpdateStmt = $mysqli->prepare("UPDATE product set image = ? where id = ?");
        $imageUpdateStmt->bind_param("ss", $uploadDir, $id);
        $imageUpdateStmt->execute();
        $imageUpdateStmt->close();
    }
    if (!$error) {
        $stmtUpdate = $mysqli->prepare("update product 
        set name = ?, price = ?, description = ?, prevPrice = ?, categoryId = ?, vendorId = ?  where id = ?");
        $stmtUpdate->bind_param(
            "sdsdsss",
            $_POST["name"],
            $_POST["price"],
            $_POST["desc"],
            $_POST["prevPrice"],
            $_POST["categoryId"],
            $vendorId,
            $id
        );
        $stmtUpdate->execute();
        $stmtUpdate->close();
        $stmt = $mysqli->prepare("SELECT 
        p.id,p.image, p.name, v.id, c.id ,p.price, p.prevPrice, p.description   
        from category as c, product as p, vendor as v
        where c.id = p.categoryId and v.id = p.vendorId and p.id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows < 1) {
            http_response_code(404);
            exit();
        }
        $product = $result->fetch_row();
        $stmt->close();
    }
}
?>
<h1>Update Category</h1>
<hr>

<form enctype="multipart/form-data" method="post" class="mx-auto" action="vendor-product-update.php">

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") foreach ($errorStrings as $str) { ?>
        <div class="alert alert-warning"><?php echo $str ?></div>
    <?php } ?>
    <input type="text" name="id" hidden value="<?php echo $product[0] ?>" id="">

    <div class="mb-3">
        <label class="form-label" for="image">Image: </label>
        <img class="item-image" src="<?php echo $product[1] ?>" />
        <input class="form-control" type="file" name="image" id="image">
    </div>
    <div class="mb-3">
        <label class="form-label" for="name">Name: </label>
        <input class="form-control" value="<?php echo $product[2] ?>" type="text" name="name" id="name">
    </div>
    <div class="mb-3">
        <label class="form-label" for="desc">Description: </label>
        <input class="form-control" value="<?php echo $product[7] ?>" type="text" name="desc" id="desc">
    </div>
    <div class="mb-3">
        <label class="form-label" for="price">Price: </label>
        <input class="form-control" value="<?php echo $product[5] ?>" type="number" , step="0.01" name="price" id="price">
    </div>
    <div class="mb-3">
        <label class="form-label" for="prevPrice">Previous Price: </label>
        <input class="form-control" type="number" value="<?php echo $product[6] ?>" step="0.01" name="prevPrice" id="prevPrice">
    </div>

    <div class="mb-3">
        <label class="form-label" for="categoryId">Category: </label>
        <select class="form-control" name="categoryId" id="categoryId">
            <?php while ($category = $categories->fetch_row()) { ?>
                <option <?php if ($category[0] == $product[4]) echo "selected" ?> value="<?php echo $category[0] ?>">
                    <?php echo $category[1] ?>
                </option>
            <?php } ?>

        </select>
    </div>
    <input class="btn btn-success form-submit" type="submit" value="Update">
</form>
<form class="mx-auto mt-2" action="vendor-product-update.php" method="post">
    <input type="text" name="id" hidden value="<?php echo $product[0] ?>" id="">
    <input type="text" name="delete" hidden value="delete" id="">
    <input class="btn btn-danger form-submit" type="submit" value="Delete">
</form>
<style>
    form {
        width: 500px;
    }

    .item-image {
        width: 300px;
        height: 300px;
        object-fit: contain;
    }
</style>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
    <script>
        <?php if (!$error) { ?>
            new Toast({
                message: "Product Successfully Updated",
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