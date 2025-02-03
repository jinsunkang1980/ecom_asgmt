<?php
session_start();
include '../includes/db.php';

// Redirect to admin login if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        echo "<p>Invalid file type. Only JPG, PNG, and GIF are allowed.</p>";
        exit;
    }

    // Handle file upload
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image_path = "uploads/" . $image;

        // Insert product into the database
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image_path);
        $stmt->execute();

        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "<p>Failed to upload image.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .add-product-form {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .add-product-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .add-product-form input,
        .add-product-form textarea,
        .add-product-form button {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-product-form button {
            background-color: black;
            color: white;
            cursor: pointer;
            width: 100%;
            padding: 10px;
        }

        .add-product-form button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <h1>Add New Product</h1>
    <div class="add-product-form">
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
