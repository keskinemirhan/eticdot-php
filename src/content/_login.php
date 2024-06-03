<?php
include "service/user-auth-utils.php";
include "service/dbconnect.php";
include "service/utils.php";
$errors = [];
$fieldErrors = [
    "password" => false,
    "email" => false
];
$emailField = '';
if ($getUserLoginInfo()->loggedIn) {
    redirect("profile.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailField = $_POST["email"];
    if (isblank_post("email", "password")) {
        array_push($errors, "Please fill all fields.");
        $fieldErrors["password"] = true;
        $fieldErrors["email"] = true;
    }
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Please enter a valid email.");
        $fieldErrors["email"] = true;
    }
    if (count($errors) < 1) {
        $userResult = $stmt_execute("select id,email, password from user where email = ?", "s", $_POST["email"]);
        if ($userResult->num_rows < 1) {
            array_push($errors, "User with given email does not exists.");
            $fieldErrors["email"] = true;
        } else {
            $user = $userResult->fetch_row();
            $email = $user[1];
            $passwordHashed = $user[2];
            if (!password_verify($_POST["password"], $passwordHashed)) {
                array_push($errors, "Invalid credentials");
                $fieldErrors["email"] = true;
                $fieldErrors["password"] = true;
            } else {
                if (session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION["userId"] = $user[0];
                $_SESSION["userIsLoggedIn"] = true;
                redirect(".");
            }
        }
    }
}

?>
<div class="auth-form-container">
    <form action="login.php" method="post" class="auth-form">
        <div class="auth-title">Login</div>

        <input value="<?php echo $emailField ?>" id="email" name="email" class="auth-input <?php if ($fieldErrors["email"]) echo "border-red" ?> " placeholder="Email*" type="email" />
        <input id="password" name="password" class="auth-input <?php if ($fieldErrors["password"]) echo "border-red" ?>" placeholder="Password*" type="password" />
        <button type="submit" id="login-submit" class="auth-submit">Login</button>
        <a class="c-register text-blue" href="register.php">Or Register</a>
    </form>
</div>
<style>
    .c-register {
        font-weight: bold;
        text-align: center;
        margin-top: 5px;
    }
</style>

<script>
    <?php if (count($errors) > 0) { ?>
        new Toast({
            message: "<?php foreach ($errors as $msg) echo $msg . " "; ?>",
            type: "danger",
        });
    <?php } ?>
    const submitButton = document.querySelector("#login-submit");
    submitButton.addEventListener("click", (e) => {
        let error = false;
        const email = document.querySelector("#email");
        const password = document.querySelector("#password");

        if (email.value === "") {
            email.classList.add("border-red");
            error = true;
        }
        if (password.value === "") {
            password.classList.add("border-red");
            error = true;
        }
        if (error) {
            new Toast({
                message: "Please fill required fields.",
                type: "danger",
            });
        }
        if (error) {
            e.preventDefault();
        }
    });
</script>

<style></style>