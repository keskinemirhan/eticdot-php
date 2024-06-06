<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "service/dbconnect.php";
    $name = $_POST["name"];
    $email = $_POST["email"];
    $error = false;
    $errorStrings = [];
    $password = $_POST["password"];
    $passwordAgain = $_POST["passwordAgain"];
    $successString = "Vendor Successfully Added";
    if (empty($name) || empty($email) || empty($password) || empty($passwordAgain)) {
        $error = true;
        array_push($errorStrings, "Please Fill All Fields");
    } else if ($password != $passwordAgain) {

        $error = true;
        array_push($errorStrings, "Passwords does not match");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        array_push($errorStrings, "Invalid Email");
    } else {
        $stmtEmailControl = $mysqli->prepare("SELECT * FROM vendor WHERE email = ?");
        $stmtEmailControl->bind_param("s", $email);
        $stmtEmailControl->execute();
        $emailControl = $stmtEmailControl->get_result();
        $stmtEmailControl->close();
        $stmtNameControl = $mysqli->prepare("SELECT * FROM vendor WHERE name = ?");
        $stmtNameControl->bind_param("s", $name);
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
            $stmtInsert = $mysqli->prepare("INSERT INTO vendor VALUES (uuid(), ?, ?, ? )");
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmtInsert->bind_param("sss", $name, $email, $passwordHash);
            $stmtInsert->execute();
        }
    }
}
?>
<h1>Add Vendor</h1>
<hr>

<form method="post" class="mx-auto" action="admin-vendor-add.php">

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") foreach ($errorStrings as $str) { ?>
        <div class="alert alert-warning"><?php echo $str ?></div>
    <?php } ?>
    <div class="mb-3">
        <label class="form-label" for="name">Name: </label>
        <input class="form-control" type="text" name="name" id="name">
    </div>
    <div class="mb-3">
        <label class="form-label" for="email">Email: </label>
        <input class="form-control" type="email" name="email" id="email">
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Password: </label>
        <input class="form-control" type="password" name="password" id="password">
    </div>
    <div class="mb-3">
        <label class="form-label" for="passwordAgain">Password Again: </label>
        <input class="form-control" type="password" name="passwordAgain" id="passwordAgain">
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
                message: "Vendor Successfully Added",
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