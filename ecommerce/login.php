<?php
include 'includes/db.php';
include 'includes/header.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare the SQL statement to fetch user details securely
    $stmt = $conn->prepare("SELECT id, password, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_password, $user_name);
        $stmt->fetch();
        
        // Verify password securely
        if (password_verify($password, $db_password)) {
            setcookie('user_id', $user_id, time() + (86400 * 7), '/');
            setcookie('user_name', $user_name, time() + (86400 * 7), '/');
            
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with this email. Please register.";
    }

    $stmt->close();
}

// Display error message if login fails
if (!empty($error_message)) {
    echo "<p style='color: red; text-align: center;'>$error_message</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #000;
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
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Centered Login Box */
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 120px;
            width: 100%;
            max-width: 400px;
            
        }

        .login-box {
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }

        .login-box label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .login-box input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            
        }

        .login-box button:hover {
            background-color: #222;
        }

        .login-box p {
            margin: 10px 0;
        }

        .login-box a {
            color: #000;
            text-decoration: none;
        }

        .login-box a:hover {
            text-decoration: underline;
        }

        /* "Why Login?" Box */
        .login-info-box {
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #ddd;
        }

        .login-info-box h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
      

    <!-- Login Container -->
    <div class="login-container">
        <!-- Login Form -->
        <div class="login-box">
            <h2>Login</h2>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <button type="submit">Login</button>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>
        </div>

        <!-- "Why Login?" Box Below -->
        <div class="login-info-box">
            <h3>Why Login?</h3>
            <p>✔ Access exclusive deals</p>
            <p>✔ Track your orders easily</p>
            <p>✔ Enjoy a personalized shopping experience</p>
        </div>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>
