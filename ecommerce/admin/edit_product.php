<?php
include '../includes/header.php';
include '../includes/db.php';

// Fetch the product details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<p>Product not found!</p>";
        exit;
    }
} else {
    echo "<p>No product ID provided!</p>";
    exit;
}

// Update the product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $newImage = $_FILES['image']['name'];

    // Handle image upload
    if (!empty($newImage)) {
        $target = "../uploads/" . basename($newImage);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            echo "<p>Invalid file type. Only JPG, PNG, and GIF are allowed.</p>";
            exit;
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Delete the old image
            if (!empty($product['image']) && file_exists("../" . $product['image'])) {
                unlink("../" . $product['image']);
            }

            $imagePath = "uploads/" . $newImage;
        } else {
            echo "<p>Failed to upload the new image.</p>";
            exit;
        }
    } else {
        $imagePath = $product['image']; // Keep the old image if no new image is uploaded
    }

    // Update the product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdssi", $name, $price, $description, $imagePath, $id);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit;
    } else {
        echo "<p>Failed to update product.</p>";
    }
}
?>

<h1>Edit Product</h1>
<form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>
    <label>Description:</label>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>
    <label>Image:</label>
    <input type="file" name="image"><br>
    <p>Current Image: <img src="../<?php echo $product['image']; ?>" alt="Product Image" width="100"></p>
    <button type="submit">Update Product</button>
</form>