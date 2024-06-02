<?php
if (!isset($_REQUEST["id"])) {
    http_response_code(404);
    exit();
}
$id = $_REQUEST["id"];
$stmt = $mysqli->prepare("select id, name, image from category where id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows < 1) {
    http_response_code(404);
    echo "<h1 style='text-align: center;'>Not found...</h1>";
    exit();
}
$category = $result->fetch_row();
$stmt->close();

$errorStrings = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["delete"])) {
    $deleteStmt = $mysqli->prepare("delete from category where id = ?");
    $deleteStmt->bind_param("s", $id);
    $deleteStmt->execute();
    $deleteStmt->close();
    unlink($category[2]);
    array_push($errorStrings, "Removed...");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_REQUEST["delete"])) {
    $name = $_POST["name"];
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["name"])) {
        unlink($category[2]);
        $uploadDir = "images/" . strval(time()) . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir);
        $imageUpdateStmt = $mysqli->prepare("UPDATE category set image = ? where id = ?");
        $imageUpdateStmt->bind_param("ss", $uploadDir, $id);
        $imageUpdateStmt->execute();
        $imageUpdateStmt->close();
    }
    $error = false;
    if (empty($name)) {
        $error = true;
        array_push($errorStrings, "Please Fill Name Field");
    }
    $stmtNameControl = $mysqli->prepare("SELECT * FROM category WHERE name = ? and id != ?");
    $stmtNameControl->bind_param("ss", $name, $id);
    $stmtNameControl->execute();
    $nameControl = $stmtNameControl->get_result();
    $stmtNameControl->close();
    if ($nameControl->num_rows > 0) {
        $error = true;
        array_push($errorStrings, "Given name already exists");
    }
    if (!$error) {
        $stmtUpdate = $mysqli->prepare("update category set name = ?  where id = ?");
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmtUpdate->bind_param("ss", $name, $id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
        $stmt = $mysqli->prepare("select id, name, image from category where id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows < 1) {
            http_response_code(404);
            exit();
        }
        $category = $result->fetch_row();
        $stmt->close();
    }
}
?>
<h1>Update Category</h1>
<hr>

<form enctype="multipart/form-data" method="post" class="mx-auto" action="admin-category-update.php">

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") foreach ($errorStrings as $str) { ?>
        <div class="alert alert-warning"><?php echo $str ?></div>
    <?php } ?>
    <input type="text" name="id" hidden value="<?php echo $category[0] ?>" id="">

    <div class="mb-3">
        <label class="form-label" for="image">Image: </label>
        <img class="item-image" src="<?php echo $category[2] ?>" />
        <input class="form-control" type="file" name="image" id="image">
    </div>
    <div class="mb-3">
        <label class="form-label" for="name">Name: </label>
        <input class="form-control" value="<?php echo $category[1] ?>" type="text" name="name" id="name">
    </div>
    <input class="btn btn-success form-submit" type="submit" value="Update">
</form>
<form class="mx-auto mt-2" action="admin-category-update.php" method="post">
    <input type="text" name="id" hidden value="<?php echo $category[0] ?>" id="">
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
                message: "Category Successfully Updated",
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