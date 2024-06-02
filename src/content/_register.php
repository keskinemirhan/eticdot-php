<?php

include "service/dbconnect.php";
$errors = [];
$fieldErrors = [
    "name" => false,
    "surname" => false,
    "email" => false,
    "password" => false,
    "passwordAgain" => false
];
$name = '';
$surname = '';
$email = '';
$password = '';
$passwordAgain = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        empty($_POST["name"]) ||
        empty($_POST["surname"]) ||
        empty($_POST["email"]) ||
        empty($_POST["password"]) ||
        empty($_POST["passwordAgain"])
    ) array_push($errors, "Please Fill All Fields");
    else {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordAgain = $_POST["passwordAgain"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Please enter valid email");
            $fieldErrors["email"] = true;
        }
        if ($password != $passwordAgain) {
            array_push($errors, "Passwords does not match");
            $fieldErrors["password"] = true;
            $fieldErrors["passwordAgain"] = true;
        } else if (strlen($password) < 6) {
            array_push($errors, "Password should be equal or longer than 6 characters");
            $fieldErrors["password"] = true;
            $fieldErrors["passwordAgain"] = true;
        }


        $emailExists = $stmt_execute("SELECT * from user where email = ?", "s", $email)->num_rows > 0;
        if ($emailExists) {
            array_push($errors, "Email already exists");
            $fieldErrors["email"] = true;
        }
        if (count($errors) < 1) {
            $stmt_execute(
                "INSERT INTO user (id,name,surname,email,password)
             values  (uuid(), ?, ?, ?, ?)",
                "ssss",
                $name,
                $surname,
                $email,
                password_hash($password, PASSWORD_BCRYPT)
            );
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            $userId = $stmt_execute("select id from user where email = ?", "s", $email)->fetch_row()[0];
            $_SESSION["userIsLoggedIn"] = true;
            $_SESSION["userId"] = $userId;
            echo "<script>window.location.replace('index.php');</script>";
        }
    }
}

?>
<div class="auth-form-container">
    <form action="register.php" method="post" class="auth-form">
        <div class="auth-title">Register</div>
        <input id="name" value="<?php echo $name ?>" name="name" class="auth-input" placeholder="Name*" type="text" />
        <input id="surname" value="<?php echo $surname ?>" name="surname" class="auth-input " placeholder=" Surname*" type="text" />
        <input id="email" value="<?php echo $email ?>" name="email" class="auth-input <?php if ($fieldErrors["email"]) echo "border-red" ?>" placeholder="Email*" type="email" />
        <input id="password" value="<?php echo $password ?>" name="password" class="auth-input <?php if ($fieldErrors["password"]) echo "border-red" ?>" placeholder="Password*" type="password" />
        <input id="retype-password" value="<?php echo $passwordAgain ?>" name="passwordAgain" class="auth-input <?php if ($fieldErrors["passwordAgain"]) echo "border-red" ?>" placeholder="Retype Password*" type="password" />
        <button id="register-submit" class="auth-submit">Register</button>
    </form>
</div>

<script>
    <?php if (count($errors) > 0) { ?>
        new Toast({
            message: "<?php foreach ($errors as $msg) echo $msg . ".  " ?> ",
            type: "danger"
        });

    <?php } ?>
    const submitButton = document.querySelector("#register-submit");
    submitButton.addEventListener("click", (e) => {
        let error = false;
        const name = document.querySelector("#name");
        const surname = document.querySelector("#surname");
        const email = document.querySelector("#email");
        const password = document.querySelector("#password");
        const retypePassword = document.querySelector("#retype-password");
        if (name.value === "") {
            name.classList.add("border-red");
            error = true;
        }
        if (surname.value === "") {
            surname.classList.add("border-red");
            error = true;
        }
        if (email.value === "") {
            email.classList.add("border-red");
            error = true;
        }
        if (password.value === "" || password.value !== retypePassword.value) {
            password.classList.add("border-red");
            retypePassword.classList.add("border-red");
            error = true;
        }
        if (error) {
            new Toast({
                message: "Please fill required fields correctly",
                type: "danger",
            });
            e.preventDefault();
        }
    });
</script>