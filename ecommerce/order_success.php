<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_COOKIE['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_COOKIE['user_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT total as total_price, created_at as order_date FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<style>
    /* Center everything */
    .order-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh; /* Keeps it centered in the viewport */
    }

    .order-box {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 400px;
        max-width: 90%;
        border: 1px solid #ddd;
    }

    .order-box h1 {
        margin-bottom: 15px;
        font-size: 1.8em;
        color: #28a745; /* Green Success Color */
    }

    .order-box p {
        font-size: 1.1em;
        margin: 10px 0;
    }

    .order-box a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: black;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .order-box a:hover {
        background-color: #333;
    }
</style>

<div class="order-container">
    <div class="order-box">
        <?php if ($result->num_rows > 0) : 
            $order = $result->fetch_assoc(); ?>
            <h1>Order Success</h1>
            <p>Your order has been placed successfully!</p>
            <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
            <a href="index.php">Return to Home</a>
        <?php else : ?>
            <p>Invalid order. <a href="index.php">Return to Home</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
