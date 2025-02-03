<?php
include 'includes/header.php';
include 'includes/db.php';


if (!isset($_COOKIE['user_id'])) {
    echo "<style>
        .empty-cart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }

        .empty-cart-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
            max-width: 90%;
            border: 1px solid #ddd;
        }

        .empty-cart-box p {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 30px;
        }

        .login-now-button {
            display: inline-block;
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: black;
            transition: background 0.3s;
        }

        .login-now-button:hover {
            background-color: #333;
        }
    </style>";

    echo "<div class='empty-cart-container'>
        <div class='empty-cart-box'>
            <p>Please login to order.</p>
            <a href='login.php' class='login-now-button'>Login</a>
        </div>
      </div>";

    include 'includes/footer.php';
    exit();
}


$user_id = $_COOKIE['user_id'];


if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $updateStmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $updateStmt->bind_param("ii", $user_id, $product_id);
        $updateStmt->execute();
    } else {
        $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insertStmt->bind_param("ii", $user_id, $product_id);
        $insertStmt->execute();
    }
    header("Location: cart.php");
    exit();
}


if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}


echo "<h1 class='cart-title'>Your Cart</h1>";



echo "<style>
    .cart-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        justify-content: center;
        margin: 20px auto;
        max-width: 1200px;
    }

    .cart-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #ddd;
        padding: 15px;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        text-align: center;
        min-height: 100%;
    }

    .cart-item img {
        width: 100px;
        height: auto;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    
    .cart-item h3 {
        margin-bottom: 5px;
        font-size: 1.2em;
    }

    .cart-item p.description {
        max-width: 200px;
        word-wrap: break-word;
        text-align: center;
        font-size: 0.9em;
        flex-grow: 1; 
        margin-bottom: 10px;
    }

    
    .cart-item-footer {
        width: 100%;
        text-align: center;
        border-top: 1px solid #ddd;
        padding-top: 10px;
        margin-top: auto;
    }

    .cart-item-footer p {
        margin: 5px 0;
        font-size: 0.9em;
    }

    .remove-btn {
        display: inline-block;
        margin-top: 5px;
        padding: 5px 10px;
        background-color: red;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9em;
    }

    .remove-btn:hover {
        background-color: darkred;
    }

    
    .cart-summary {
        text-align: center;
        margin-top: 30px;
    }

    .cart-summary h2 {
        font-size: 1.5em;
        margin-bottom: 15px;
    }

    .checkout-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: black;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.2em;
        transition: background 0.3s;
    }

    .checkout-button:hover {
        background-color: #333;
    }

    .cart-title {
    text-align: center;
    font-size: 2em; /* Optional: Adjusts the font size */
    margin-top: 20px; /* Adds space above */
}
</style>";

$stmt = $conn->prepare("SELECT c.id AS cart_id, p.id AS product_id, p.name, p.description, p.price, p.image, c.quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $total_price = 0;
    echo '<div class="cart-container">'; 

    while ($row = $result->fetch_assoc()) {
        $total_item_price = $row['price'] * $row['quantity'];
        $total_price += $total_item_price;
        ?>
        <div class="cart-item">
            <?php echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">' ?>
            <h3><?php echo $row['name']; ?></h3>
            <p class="description"><?php echo $row['description']; ?></p>

            <!-- Footer section for aligned Price, Quantity, Total, and Remove -->
            <div class="cart-item-footer">
                <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                <p>Qty: <?php echo $row['quantity']; ?></p>
                <p>Total: $<?php echo number_format($total_item_price, 2); ?></p>
                <a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="remove-btn">Remove</a>
            </div>
        </div>
        <?php
    }
    echo '</div>'; // Close the cart grid container
    ?>

    <!-- Centered Total Price and Checkout Button -->
    <div class="cart-summary">
        <h2>Total Price: $<?php echo number_format($total_price, 2); ?></h2>
        <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
    </div>

    <?php
    
} else {
    echo "<style>
    .empty-cart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70vh;
    }

    .empty-cart-box {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 400px;
        max-width: 90%;
        border: 1px solid #ddd;
    }

    .empty-cart-box p {
        font-size: 1.2em;
        color: #333;
        margin-bottom: 30px;
    }

    .shop-now-button {
        display: inline-block;
        color: white;
        font-weight: bold;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: black;
        transition: background 0.3s;
    }

    .shop-now-button:hover {
        background-color: #333;
    }
</style>";

echo "<div class='empty-cart-container'>
        <div class='empty-cart-box'>
            <p>Your cart is empty.</p>
            <a href='products.php' class='shop-now-button'>Shop Now</a>
        </div>
      </div>";

}

include 'includes/footer.php';
?>
