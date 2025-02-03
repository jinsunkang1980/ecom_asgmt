<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/header.php';
include 'includes/db.php';


if (!isset($_COOKIE['user_id'])) {
    echo "<p>Please <a href='login.php'>login</a> to proceed with checkout.</p>";
    include 'includes/footer.php';
    exit();
}

$user_id = $_COOKIE['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

   
    $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Error fetching cart data: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $total_price = 0;
        $order_items = [];

        while ($row = $result->fetch_assoc()) {
            $total_price += $row['price'] * $row['quantity'];
            $order_items[] = [
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity']
            ];
        }

        echo "Total Price: $total_price<br>";

        $order_stmt = $conn->prepare("INSERT INTO orders (user_id, total, created_at) VALUES (?, ?, NOW())");
        $order_stmt->bind_param("id", $user_id, $total_price);
        if (!$order_stmt->execute()) {
            die("Error inserting order: " . $order_stmt->error);
        }
        $order_id = $order_stmt->insert_id;
        echo "Order ID: $order_id<br>";

        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        foreach ($order_items as $item) {
            $item_stmt->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
            if (!$item_stmt->execute()) {
                die("Error inserting order item: " . $item_stmt->error);
            }
        }

        $clear_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_stmt->bind_param("i", $user_id);
        if (!$clear_stmt->execute()) {
            die("Error clearing cart: " . $clear_stmt->error);
        }

        header("Location: order_success.php?order_id=$order_id");
        exit();
    } else {
        echo "<p>Your cart is empty. <a href='products.php'>Shop now</a>.</p>";
    }

}


echo "<style>
    /* Center the checkout box */
    .checkout-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70vh;
    }

    .checkout-box {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 400px;
        max-width: 90%;
        border: 1px solid #ddd;
    }

    .checkout-box h1 {
        margin-bottom: 15px;
        font-size: 1.8em;
        color: #007bff; /* Blue color */
    }

    .checkout-box p {
        font-size: 1.1em;
        margin-bottom: 15px;
        color: #333;
    }

    .checkout-button {
        display: inline-block;
        padding: 12px 20px;
        background-color: black;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.2em;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }

    .checkout-button:hover {
        background-color: #333;
    }
</style>";

echo "<div class='checkout-container'>
        <div class='checkout-box'>
            <h1>Checkout</h1>
            <form method='POST' action='checkout.php'>
                <p>Click the button below to confirm your order.</p>
                <button type='submit' class='checkout-button'>Confirm Order</button>
            </form>
        </div>
      </div>";



include 'includes/footer.php';
?>
