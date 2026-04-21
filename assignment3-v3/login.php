<?php
session_start();
require "db.php";

$error = $message = "";

// Show logout message if available
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

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
        $email    = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if (strlen($password) < 6) {
            $error = "Password must be at least 6 characters!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format!";
        } else {
            // Check if username or email already exists
            $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Username or email already taken!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    $message = "Account created successfully! You can now log in.";
                } else {
                    $error = "Registration failed. Try again.";
                }
                $stmt->close();
            }
            $check->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SecureAuth - Login & Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔐</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="light-theme">

    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="form-box">
            <!-- Theme toggle -->
            <div class="theme-toggle">
                <i class="fas fa-sun"></i>
                <label class="theme-switch">
                    <input type="checkbox" id="themeSwitch">
                    <span class="theme-slider"></span>
                </label>
                <i class="fas fa-moon"></i>
                <span class="label">Theme</span>
            </div>

            <div class="header">
                <h2 id="formTitle">Welcome Back!</h2>
                <p id="formSubtitle">Enter your credentials to access your secure area.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="toggle-buttons">
                <button class="toggle-btn active" id="loginTab">Login</button>
                <button class="toggle-btn" id="registerTab">Register</button>
            </div>

            <!-- Login Form -->
            <form id="loginForm" class="active" method="POST" autocomplete="off">
                <input type="hidden" name="action" value="login">

                <div class="input-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" name="username" placeholder="Enter your username" required>
                    <i class="fas fa-user input-icon"></i>
                </div>

                <div class="input-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-password" onclick="togglePass('loginPassword', this)"></i>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Login</span>
                </button>
            </form>

            <!-- Register Form -->
            <form id="registerForm" method="POST" autocomplete="off">
                <input type="hidden" name="action" value="register">

                <div class="input-group">
                    <label for="regUsername">Username</label>
                    <input type="text" id="regUsername" name="username" placeholder="Choose a username" required>
                    <i class="fas fa-user input-icon"></i>
                </div>

                <div class="input-group">
                    <label for="regEmail">Email</label>
                    <input type="email" id="regEmail" name="email" placeholder="Enter your email" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <div class="input-group">
                    <label for="regPassword">Password</label>
                    <input type="password" id="regPassword" name="password" placeholder="Create a strong password" required minlength="6">
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-password" onclick="togglePass('regPassword', this)"></i>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Create Account</span>
                </button>
            </form>

            <div class="footer-text">
                Secured with 🔒 PHP + MySQL + password_hash()
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const formTitle = document.getElementById('formTitle');
        const formSubtitle = document.getElementById('formSubtitle');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
            formTitle.textContent = "Welcome Back!";
            formSubtitle.textContent = "Enter your credentials to access your secure area.";
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
            formTitle.textContent = "Create Account";
            formSubtitle.textContent = "Join us today and secure your digital identity.";
        });

        // Theme toggle
        const themeSwitch = document.getElementById('themeSwitch');
        const body = document.body;

        themeSwitch.addEventListener('change', () => {
            if (themeSwitch.checked) {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-theme');
                body.classList.add('light-theme');
                localStorage.setItem('theme', 'light');
            }
        });

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-theme');
            themeSwitch.checked = true;
        } else {
            body.classList.add('light-theme');
        }

        // Password visibility toggle
        function togglePass(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>