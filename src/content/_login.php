<div class="auth-form-container">
    <form class="auth-form">
        <div class="auth-title">Login</div>

        <input id="email" class="auth-input" placeholder="Email*" type="email" />
        <input id="password" class="auth-input" placeholder="Password*" type="password" />
        <button id="login-submit" class="auth-submit">Login</button>
        <a class="c-register text-blue" href="/register.php">Or Register</a>
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
    const submitButton = document.querySelector("#login-submit");
    submitButton.addEventListener("click", (e) => {
        e.preventDefault();
        let error = false;
        let users = JSON.parse(localStorage.getItem("users"));
        if (!users) users = [];
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
                message: "Please fill required fields correctly",
                type: "danger",
            });
        }
        const userIndex = users.findIndex(
            (usr) => usr.email === email.value && usr.password === password.value
        );
        if (userIndex === -1) {
            new Toast({
                message: "Invalid credentials",
                type: "danger",
            });
            password.classList.add("border-red");
            email.classList.add("border-red");

            error = true;
        }
        if (!error) {
            const user = users[userIndex];
            localStorage.setItem(
                "logUser",
                JSON.stringify({
                    name: user.name,
                    surname: user.surname,
                    email: user.email,
                    password: user.password,
                })
            );
            window.location.replace("/");
        }
    });
</script>

<style></style>