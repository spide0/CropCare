<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #6f42c1, #4a306d), url('https://source.unsplash.com/1920x1080/?nature,abstract');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: white;
        }

        .login-container, .register-container {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            animation: slideIn 0.8s ease-out;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.2rem;
        }

        .form-group {
            margin-bottom: 15px;
            animation: fadeIn 1s ease-in-out;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            background: linear-gradient(to right, #6f42c1, #4a306d);
            color: white;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            animation: bounce 1.5s infinite alternate;
            transition: transform 0.2s;
            box-sizing: border-box;
        }

        .btn:hover {
            background: linear-gradient(to right, #4a306d, #6f42c1);
            transform: scale(1.05);
        }

        .register {
            text-align: center;
            margin-top: 10px;
        }

        .register a {
            color: #ffc107;
            text-decoration: none;
            cursor: pointer;
        }

        .register a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form onsubmit="return loginUser()">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login" class="btn">
            </div>
        </form>
        <div class="register">
            <p>Don't have an account? <a onclick="showRegister()">Register</a></p>
        </div>
    </div>

    <div class="register-container" id="register-container" style="display:none;">
        <h1>Register</h1>
        <form onsubmit="return registerUser()">
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>
            </div>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>
            </div>
            <div class="form-group">
                <label for="new-username">Username</label>
                <input type="text" id="new-username" name="new-username" required>
            </div>
            <div class="form-group">
                <label for="new-password">Password</label>
                <input type="password" id="new-password" name="new-password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register" class="btn">
            </div>
        </form>
        <div class="register">
            <p>Already have an account? <a onclick="showLogin()">Login</a></p>
        </div>
    </div>

    <script>
        function loginUser() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            const storedUsername = localStorage.getItem("username");
            const storedPassword = localStorage.getItem("password");

            if (username === storedUsername && password === storedPassword) {
                alert("Login successful!");
                window.location.href = "dashboard.html"; // Redirect to dashboard
                return false;
            } else {
                alert("Invalid username or password!");
                return false;
            }
        }

        function registerUser() {
            const firstName = document.getElementById("first-name").value;
            const lastName = document.getElementById("last-name").value;
            const newUsername = document.getElementById("new-username").value;
            const newPassword = document.getElementById("new-password").value;

            if (firstName && lastName && newUsername && newPassword) {
                localStorage.setItem("firstName", firstName);
                localStorage.setItem("lastName", lastName);
                localStorage.setItem("username", newUsername);
                localStorage.setItem("password", newPassword);
                alert("Registration successful! You can now login.");
                showLogin();
            } else {
                alert("Please fill out all fields.");
            }
            return false; // Prevent actual form submission
        }

        function showRegister() {
            document.querySelector(".login-container").style.display = "none";
            document.getElementById("register-container").style.display = "block";
        }

        function showLogin() {
            document.getElementById("register-container").style.display = "none";
            document.querySelector(".login-container").style.display = "block";
        }
    </script>
</body>
</html>
