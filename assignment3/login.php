<?php
session_start();
require "db.php";

$error = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action == "login") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    }

    elseif ($action == "register") {
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Basic validation
        if (strlen($password) < 6) {
            $error = "Password must be at least 6 characters!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Account created! You can now log in.";
            } else {
                $error = "Username or email already exists!";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureAuth - Login & Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔐</text></svg>">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <div class="header">
                <h2 id="formTitle">Welcome Back!</h2>
                <p id="formSubtitle">Enter your credentials to access your account</p>
            </div>

            <?php if($error): ?>
                <div class="alert error shake"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if($message): ?>
                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="toggle-buttons">
                <button type="button" class="toggle-btn active" data-form="login">Login</button>
                <button type="button" class="toggle-btn" data-form="register">Register</button>
            </div>

            <form id="authForm" action="" method="POST">
                <input type="hidden" name="action" value="login">

                <div class="input-group" id="usernameGroup">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required autocomplete="username">
                    <span class="input-icon">👤</span>
                </div>

                <div class="input-group hidden" id="emailGroup">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email" autocomplete="email">
                    <span class="input-icon">📧</span>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required autocomplete="current-password">
                    <span class="input-icon toggle-password">👁️</span>
                </div>

                <button type="submit" class="submit-btn">
                    <span class="btn-text">Login</span>
                    <span class="loading hidden">Loading...</span>
                </button>
            </form>

            <p class="footer-text">
                Made with ❤️ by <strong>You</strong> | Secure Login System 2025
            </p>
        </div>

        <div class="background-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <script>
        const loginBtn = document.querySelector('[data-form="login"]');
        const registerBtn = document.querySelector('[data-form="register"]');
        const form = document.getElementById('authForm');
        const title = document.getElementById('formTitle');
        const subtitle = document.getElementById('formSubtitle');
        const usernameGroup = document.getElementById('usernameGroup');
        const emailGroup = document.getElementById('emailGroup');
        const submitBtn = document.querySelector('.submit-btn');
        const btnText = document.querySelector('.btn-text');
        const loading = document.querySelector('.loading');
        const hiddenInput = form.querySelector('input[name="action"]');

        registerBtn.addEventListener('click', () => {
            toggleForm('register');
        });

        loginBtn.addEventListener('click', () => {
            toggleForm('login');
        });

        document.querySelector('.toggle-password').addEventListener('click', () => {
            const pass = document.getElementById('password');
            if (pass.type === 'password') {
                pass.type = 'text';
                document.querySelector('.toggle-password').textContent = '🙈';
            } else {
                pass.type = 'password';
                document.querySelector('.toggle-password').textContent = '👁️';
            }
        });

        function toggleForm(type) {
            loginBtn.classList.toggle('active', type === 'login');
            registerBtn.classList.toggle('active', type === 'register');

            if (type === 'register') {
                title.textContent = "Create Account";
                subtitle.textContent = "Join us today! It takes only a minute";
                emailGroup.classList.remove('hidden');
                usernameGroup.querySelector('input').required = true;
                submitBtn.querySelector('.btn-text').textContent = "Create Account";
                hiddenInput.value = "register";
            } else {
                title.textContent = "Welcome Back!";
                subtitle.textContent = "Enter your credentials to access your account";
                emailGroup.classList.add('hidden');
                submitBtn.querySelector('.btn-text').textContent = "Login";
                hiddenInput.value = "login";
            }
        }

        // Loading state
        form.addEventListener('submit', () => {
            btnText.classList.add('hidden');
            loading.classList.remove('hidden');
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>