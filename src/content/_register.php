<div class="auth-form-container">
    <form class="auth-form">
        <div class="auth-title">Register</div>
        <input id="name" class="auth-input" placeholder="Name*" type="text" />
        <input id="surname" class="auth-input" placeholder="Surname*" type="text" />
        <input id="email" class="auth-input" placeholder="Email*" type="email" />
        <input id="password" class="auth-input" placeholder="Password*" type="password" />
        <input id="retype-password" class="auth-input" placeholder="Retype Password*" type="password" />
        <button id="register-submit" class="auth-submit">Register</button>
    </form>
</div>

<script>
    const submitButton = document.querySelector("#register-submit");
    submitButton.addEventListener("click", (e) => {
        e.preventDefault();
        let error = false;
        let users = JSON.parse(localStorage.getItem("users"));
        if (!users) users = [];
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
        }
        if (users.findIndex((usr) => usr.email === email.value) !== -1) {
            new Toast({
                message: "Email already exists",
                type: "danger",
            });
            error = true;
        }
        if (!error) {
            users.push({
                name: name.value,
                surname: surname.value,
                email: email.value,
                password: password.value,
            });
            localStorage.setItem("users", JSON.stringify(users));
            localStorage.setItem(
                "logUser",
                JSON.stringify({
                    name: name.value,
                    surname: surname.value,
                    email: email.value,
                    password: password.value,
                })
            );
            window.location.replace("/");
        }
    });
</script>