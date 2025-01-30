<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the product to get the image path
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<p>Product not found!</p>";
        exit;
    }

    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete the image file
        if (!empty($product['image']) && file_exists("../" . $product['image'])) {
            unlink("../" . $product['image']);
        }

        header("Location: products.php");
        exit;
    } else {
        echo "<p>Failed to delete product.</p>";
    }
} else {
    echo "<p>No product ID provided!</p>";
}
?>