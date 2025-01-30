<?php
include '../includes/header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        echo "<p>Invalid file type. Only JPG, PNG, and GIF are allowed.</p>";
        exit;
    }

    // Handle file upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Insert into database
            $imagePath = "uploads/" . $image;

            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $name, $price, $description, $imagePath);
            $stmt->execute();
            header("Location: products.php");
            exit;
        } else {
            echo "<p>Failed to move the uploaded file to the target directory.</p>";
        }
    } else {
        echo "<p>Error uploading file: " . $_FILES['image']['error'] . "</p>";
    }
}
?>

<h1>Add Product</h1>
<form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" required><br>
    <label>Price:</label>
    <input type="number" step="0.01" name="price" required><br>
    <label>Description:</label>
    <textarea name="description" required></textarea><br>
    <label>Image:</label>
    <input type="file" name="image" required><br>
    <button type="submit">Add Product</button>
</form>