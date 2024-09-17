<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <style>
        .error {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Register</h1>
    <form id="registerForm" action="register_process.php" method="post">
        <div>
            <label for="id">User name:</label>
            <input id="id" name="id" type="text" size="30" maxlength="100" required>
            <span id="idError" class="error">User ID should be composed within 6 to 12 alphanumeric characters</span>
        </div>
        <br>
        <div>
            <label for="pwd">Password:</label>
            <input id="pwd" name="pwd" type="password" size="30" maxlength="100" required>
            <span id="pwdError" class="error">Password require at least one uppercase letter, one lowercase letter, one digit, one special symbol, and a minimum length of 8 characters</span>
        </div>
        <br>
        <div>
            <label for="name">Name:</label>
            <input id="name" name="name" type="text" size="30" maxlength="100">
            <span id="nameError" class="error">Name should be composed with English characters start with capital letter</span>
        </div>
        <br>
        <div>
            <label>Email:</label>
            <input id="email" name="email" size="30" maxlength="100" required>
            <span id="emailError" class="error">Email should contain @ character</span>
        </div>
        <br>
        <br>
        <input type="submit" value="Submit">
    </form>
    <a href="login.php">Back to login</a>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });

        function validateForm() {
            const id = document.getElementById('id').value;
            const pwd = document.getElementById('pwd').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            let isValid = true;

            if (!/^[a-zA-Z0-9]{6,12}$/.test(id)) {
                document.getElementById('idError').style.display = 'inline';
                isValid = false;
            } else {
                document.getElementById('idError').style.display = 'none';
            }

            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[?!@#$%^&*])[a-zA-Z\d?!@#$%^&*]{8,}$/.test(pwd)) {
                document.getElementById('pwdError').style.display = 'inline';
                isValid = false;
            } else {
                document.getElementById('pwdError').style.display = 'none';
            }

            if (!/^[A-Z]([a-zA-Z]|\s)*$/.test(name)) {
                document.getElementById('nameError').style.display = 'inline';
                isValid = false;
            } else {
                document.getElementById('nameError').style.display = 'none';
            }

            if (!/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(email)) {
                document.getElementById('emailError').style.display = 'inline';
                isValid = false;
            } else {
                document.getElementById('emailError').style.display = 'none';
            }

            return isValid;
        }
    </script>
</body>
</html>