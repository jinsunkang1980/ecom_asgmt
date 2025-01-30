<?php
include '../includes/header.php';
include '../includes/db.php';

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>Product Management</h1>";
echo "<a href='add_product.php'>Add New Product</a>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['price']}</td>
        <td>
            <a href='edit_product.php?id={$row['id']}'>Edit</a> | 
            <a href='delete_product.php?id={$row['id']}'>Delete</a>
        </td>
    </tr>";
}

echo "</table>";

include '../includes/footer.php';
?>