<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theos Canada by Jinsun Kang</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="nav-left">
        <a href="index.php">Home</a>
        <a href="products.php">All Products</a>
        <a href="category.php?id=1">Accessories</a>
        <a href="category.php?id=2">Tops</a>
        <a href="category.php?id=3">Botooms</a>
        <a href="cart.php">Cart</a>
    </div>
    <div class="nav-right">
        <?php if (isset($_COOKIE['user_id'])): ?>
            <div class="user-info">
                <span class="username">Hello, <?= htmlspecialchars($_COOKIE['user_name']) ?>!</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        <?php else: ?>
            <div class="auth-links">
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            </div>
        <?php endif; ?>
    </div>
</nav>



<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        padding-top: 60px;
    }

    /* Navigation Bar */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: black;
        color: white;
        padding: 10px 20px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
        font-size: 16px;
    }

    .navbar a:hover {
        text-decoration: underline;
    }

    .nav-left {
        display: flex;
        align-items: center;
    }

    .nav-right {
        display: flex;
        align-items: center;
        margin-right: 40px;
}
    }

    .auth-links {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-info {
    display: flex;
    align-items: center;
    gap: 5px;
    }

    .username {
        font-weight: bold;
    }

    .logout-btn {
        color: red;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: transparent;
        border: 1px solid red;
        text-decoration: none;
        transition: background 0.3s, color 0.3s;
    }

    .logout-btn:hover {
        background-color: red;
        color: white;
        text-decoration: none;
    }
    .auth-links {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-right: 10px;
}
</style>
