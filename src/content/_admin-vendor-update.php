<?php
if (!isset($_REQUEST["id"])) {
    http_response_code(404);
    exit();
}
$id = $_REQUEST["id"];
$stmt = $mysqli->prepare("select id, name, email from vendor where id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows < 1) {
    http_response_code(404);
    echo "<h1 style='text-align: center;'>Not found...</h1>";
    exit();
}
$vendor = $result->fetch_row();
$stmt->close();

$errorStrings = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["delete"])) {
    $deleteStmt = $mysqli->prepare("delete from vendor where id = ?");
    $deleteStmt->bind_param("s", $id);
    $deleteStmt->execute();
    $deleteStmt->close();
    array_push($errorStrings, "Removed...");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_REQUEST["delete"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $error = false;
    $password = $_POST["password"];
    $passwordAgain = $_POST["passwordAgain"];
    if (empty($name) || empty($email)) {
        $error = true;
        array_push($errorStrings, "Please Fill All Fields");
    }
    if (!empty($password) || !empty($passwordAgain)) {
        if ($password != $passwordAgain) {
            $error = true;
            array_push($errorStrings, "Passwords does not match");
        }
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        array_push($errorStrings, "Invalid Email");
    } else {
        $stmtEmailControl = $mysqli->prepare("SELECT * FROM vendor WHERE email = ? and id != ?");
        $stmtEmailControl->bind_param("ss", $email, $id);
        $stmtEmailControl->execute();
        $emailControl = $stmtEmailControl->get_result();
        $stmtEmailControl->close();
        $stmtNameControl = $mysqli->prepare("SELECT * FROM vendor WHERE name = ? and id != ?");
        $stmtNameControl->bind_param("ss", $name, $id);
        $stmtNameControl->execute();
        $nameControl = $stmtNameControl->get_result();
        $stmtNameControl->close();
        if ($emailControl->num_rows > 0) {
            $error = true;
            array_push($errorStrings, "Given email already exists");
        }
        if ($nameControl->num_rows > 0) {
            $error = true;
            array_push($errorStrings, "Given name already exists");
        }
        if (!$error) {
            $stmtInsert = $mysqli->prepare("update vendor set name = ? , email = ?, password = ? where id = ?");
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmtInsert->bind_param("ssss", $name, $email, $password, $id);
            $stmtInsert->execute();
            $stmt = $mysqli->prepare("select id, name, email from vendor where id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows < 1) {
                http_response_code(404);
                exit();
            }
            $vendor = $result->fetch_row();
            $stmt->close();
        }
    }
}
?>
<h1>Update Vendor</h1>
<hr>

<form method="post" class="mx-auto" action="admin-vendor-update.php">

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") foreach ($errorStrings as $str) { ?>
        <div class="alert alert-warning"><?php echo $str ?></div>
    <?php } ?>
    <input type="text" name="id" hidden value="<?php echo $vendor[0] ?>" id="">

    <div class="mb-3">
        <label class="form-label" for="name">Name: </label>
        <input class="form-control" value="<?php echo $vendor[1] ?>" type="text" name="name" id="name">
    </div>
    <div class="mb-3">
        <label class="form-label" for="email">Email: </label>
        <input class="form-control" value="<?php echo $vendor[2] ?>" type="email" name="email" id="email">
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Password: </label>
        <input class="form-control" type="password" name="password" id="password">
    </div>
    <div class="mb-3">
        <label class="form-label" for="passwordAgain">Password Again: </label>
        <input class="form-control" type="password" name="passwordAgain" id="passwordAgain">
    </div>
    <input class="btn btn-success form-submit" type="submit" value="Update">
</form>
<form class="mx-auto mt-2" action="admin-vendor-update.php" method="post">
    <input type="text" name="id" hidden value="<?php echo $vendor[0] ?>" id="">
    <input type="text" name="delete" hidden value="delete" id="">
    <input class="btn btn-danger form-submit" type="submit" value="Delete">
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
                message: "Vendor Successfully Updated",
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