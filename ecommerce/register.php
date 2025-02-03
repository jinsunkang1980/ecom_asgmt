<?php
include 'includes/db.php'; // Database connection
include 'includes/header.php'; // Include the header

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate user inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if (!$email) {
        $error_message = "Invalid email format.";
    } else {
        // Check if the email is already registered
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "This email is already registered. Please log in.";
        } else {
            // Hash the password before storing
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash_password);

            if ($stmt->execute()) {
                // Redirect to the login page
                header('Location: login.php');
                exit;
            } else {
                $error_message = "Error registering user: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// Display any error messages
if (!empty($error_message)) {
    echo "<p style='color: red; text-align: center;'>$error_message</p>";
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

     
        .register-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 200px; /* Adjusts for the navbar */
            width: 80%;
            max-width: 400px;
        }

        .register-box {
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }

        .register-box label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .register-box input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .register-box button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .register-box button:hover {
            background-color: #222;
        }

        .register-box p {
            margin: 10px 0;
        }

        .register-box a {
            color: #000;
            text-decoration: none;
        }

        .register-box a:hover {
            text-decoration: underline;
        }

      
        .register-info-box {
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #ddd;
        }

        .register-info-box h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .error-message {
           color: red;
           font-size: 16px;
           font-weight: bold;
           text-align: center;
        margin-bottom: 10px; 
}
    </style>
</head>
<body>
   
    
    <div class="register-container">
     
        <div class="register-box">
    
    <?php if (!empty($error_message)) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <h2>Register</h2>

    <form method="POST" action="">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Register</button>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>


        <!-- "Why Register?" Box Below -->
        <div class="register-info-box">
            <h3>Why Register?</h3>
            <p>✔ Get access to exclusive deals</p>
            <p>✔ Track your orders easily</p>
            <p>✔ Enjoy a personalized shopping experience</p>
        </div>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>
