<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SecureAuth</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>text</text></svg>">

    <style>
        /* Override only the center shape so it stays below the card & logout button */
        .dashboard-body .background-shapes .shape:nth-child(3) {
            width: 180px !important;
            height: 180px !important;
            top: 68% !important;
            opacity: 0.6;
            filter: blur(20px);
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Floating background shapes (center one is now harmless) -->
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-card">
            <h1>Hello, <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>!</h1>
            <p>You are now logged in to your secure account.</p>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <script>
        // Restore user's saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.add('light-theme');
        }
    </script>
</body>
</html>