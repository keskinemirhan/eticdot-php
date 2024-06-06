<?php
$redirect = "vendor-panel.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "service/dbconnect.php";
    if (!isset($_REQUEST["email"]) || !isset($_REQUEST["password"])) {
        http_response_code(400);
        exit();
    }
    $toast = '                   
    new Toast({
        message: "Invalid credentials",
        type: "danger",
    });';
    $error = false;

    $email = $_REQUEST["email"];
    $password = $_REQUEST["password"];

    $stmt = $mysqli->prepare("SELECT id, password FROM vendor WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        $error = true;
    } else {
        $vendor = $result->fetch_row();

        if (password_verify($password, $vendor[1])) {
            session_start();
            $_SESSION["vendorId"] = $vendor[0];
            $_SESSION["isLoggedIn"] = TRUE;
            header("Location: " . $redirect);
        } else {
            $error = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eticdot - Login</title>
    <?php include "views/_headcontent.php" ?>

</head>

<body>
    <main style="min-height: 100vh;">
        <main>
            <div class="auth-form-container">
                <form action="vendor-login.php" method="post" class="auth-form">
                    <div class="auth-title">Vendor Login</div>
                    <input name="email" id="email" class="auth-input" placeholder="Email*" type="email" />
                    <input name="password" id="password" class="auth-input" placeholder="Password*" type="password" />
                    <button id="login-submit" class="auth-submit">Login</button>
                </form>
            </div>
        </main>
        <?php include "views/_scripts.php" ?>
        <script>
            <?php
            if ($error) echo $toast;
            ?>
            const submitButton = document.querySelector("#login-submit");
            const form = document.querySelector("form");
            form.addEventListener("submit", (e) => {
                let error = false;
                let users = JSON.parse(localStorage.getItem("users"));
                if (!users) users = [];
                const email = document.querySelector("#email");
                const password = document.querySelector("#password");

                if (email.value === "") {
                    email.classList.add("border-red");
                    error = true;
                    e.preventDefault();

                }
                if (password.value === "") {
                    password.classList.add("border-red");
                    error = true;
                    e.preventDefault();

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
    </main>



    <?php include "views/_footer.php" ?>
</body>

</html>