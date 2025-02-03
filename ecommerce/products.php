<?php
include 'includes/header.php';
include 'includes/db.php';

// Fetch all categories
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);

if (!$category_result) {
    die("Error fetching categories: " . $conn->error);
}
$categories = $category_result->fetch_all(MYSQLI_ASSOC);

// Fetch products
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : null;
$product_sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id";

if ($selected_category) {
    $product_sql .= " WHERE p.category_id = ?";
    $stmt = $conn->prepare($product_sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $selected_category);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    $product_result = $stmt->get_result();
} else {
    $product_result = $conn->query($product_sql);
    if (!$product_result) {
        die("Error fetching products: " . $conn->error);
    }
}

$products = $product_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        .products-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* ✅ Fixed 5 columns */
            gap: 20px;
            margin: 20px auto;
            max-width: 1200px;
            padding: 0 20px;
        }

        @media (max-width: 1200px) {
            .products-container {
                grid-template-columns: repeat(3, 1fr); /* 3 columns on smaller screens */
            }
        }

        @media (max-width: 768px) {
            .products-container {
                grid-template-columns: repeat(2, 1fr); /* 2 columns on mobile */
            }
        }

        .products-heading {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
            margin: 50px 0 20px 0; /* ✅ Added margin */
        }

        .product {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .product img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .category-filter {
            text-align: center;
            margin: 20px 0; /* ✅ Ensure filter is visible */
        }

        .product-footer a {
            display: inline-block;
            width: 150px;
            height: 40px;
            line-height: 40px;
            background-color:rgb(0, 1, 2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .product-footer a:hover {
            background-color:rgb(0, 4, 8);
        }
        
    </style>
</head>
<body>
    <div class="products-heading">Our Products</div>

    <!-- Category Filter -->
    <div class="category-filter">
        <form method="GET" action="">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $selected_category == $category['id'] ? 'selected' : '' ?>>
                        <?= $category['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Product Grid -->
    <div class="products-container">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                <h3><?= $product['name'] ?></h3>
                <p class="description"><?= $product['description'] ?></p>
                <div class="product-footer">
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <a href="cart.php?add=<?= $product['id'] ?>">Add to Cart</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
