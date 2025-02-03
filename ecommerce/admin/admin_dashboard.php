<?php
session_start();
include '../includes/db.php';

// Redirect to admin login if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all products
$products_sql = "SELECT * FROM products";
$products_result = $conn->query($products_sql);
$products = $products_result->fetch_all(MYSQLI_ASSOC);

// Fetch all orders
$orders_sql = "SELECT o.id, o.user_id, o.total, o.created_at, u.name AS user_name 
               FROM orders o 
               JOIN users u ON o.user_id = u.id";
$orders_result = $conn->query($orders_sql);
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px; /* Space between the title and buttons */
        }

        .dashboard-header {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        .dashboard-section {
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-product-button, .logout-button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-product-button {
            background-color: black;
        }

        .add-product-button:hover {
            background-color: #333;
        }

        .logout-button {
            background-color: red;
        }

        .logout-button:hover {
            background-color: darkred;
        }

        .action-links a {
            margin-right: 10px;
            color: black;
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <!-- Buttons Container -->
    <div class="dashboard-header">
        <a href="add_product.php" class="add-product-button">Add New Product</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>

    <!-- Products Section -->
    <div class="dashboard-section">
        <h2>Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['description'] ?></td>
                        <td>$<?= $product['price'] ?></td>
                        <td><img src="../<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="50"></td>
                        <td class="action-links">
                            <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                            <a href="delete_product.php?id=<?= $product['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Orders Section -->
    <div class="dashboard-section">
        <h2>Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['user_name'] ?></td>
                        <td>$<?= $order['total'] ?></td>
                        <td><?= $order['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
