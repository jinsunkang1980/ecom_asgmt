<?php
include 'includes/header.php';
include 'includes/db.php';

// Get the category ID from the URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get the search term from the URL (if any)
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch the category name
$category_sql = "SELECT name FROM categories WHERE id = ?";
$stmt = $conn->prepare($category_sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category_result = $stmt->get_result();
$category = $category_result->fetch_assoc();

if (!$category) {
    echo "<p>Category not found.</p>";
    include 'includes/footer.php';
    exit();
}

// Fetch products for the selected category and search term
$product_sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ?";

if (!empty($search_term)) {
    $product_sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $stmt = $conn->prepare($product_sql);
    $search_param = "%$search_term%";
    $stmt->bind_param("iss", $category_id, $search_param, $search_param);
} else {
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $category_id);
}

$stmt->execute();
$product_result = $stmt->get_result();
$products = $product_result->fetch_all(MYSQLI_ASSOC);
?>

<style>
    .products-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin: 20px auto;
        max-width: 1200px;
    }

    .category-heading {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        margin: 50px 0 20px 0;
    }

    .search-bar {
        text-align: center;
        margin: 20px 0;
    }

    .search-bar input[type="text"] {
        padding: 10px;
        width: 300px;
        max-width: 80%;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
    }

    .search-bar button {
        padding: 10px 20px;
        background-color: black;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }

    .search-bar button:hover {
        background-color: #333;
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
        height: 100%;
    }

    .product img {
        width: 100%;
        aspect-ratio: 5 / 8;
        object-fit: cover;
        border-radius: 5px;
    }

    .product h3 {
        font-size: 1.2em;
        margin: 10px 0;
    }

    .product p.description {
        margin: 10px 0;
        font-size: 1em;
        min-height: 60px;
    }

    .product-footer {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .product-footer p.price {
        font-size: 1.2em;
        font-weight: bold;
        margin: 10px 0;
    }

    .product-footer a {
        display: inline-block;
        width: 150px;
        height: 40px;
        line-height: 40px;
        background-color: black;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .product-footer a:hover {
        background-color: #333;
    }
</style>

<div class="category-heading"><?= $category['name'] ?></div>

<!-- Search Bar -->
<div class="search-bar">
    <form method="GET" action="">
        <input type="hidden" name="id" value="<?= $category_id ?>">
        <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search_term) ?>">
        <button type="submit">Search</button>
    </form>
</div>

<!-- Product Grid -->
<div class="products-container">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                <h3><?= $product['name'] ?></h3>
                <p class="description"><?= $product['description'] ?></p>
                <div class="product-footer">
                    <p class="price">$<?= $product['price'] ?></p>
                    <a href="cart.php?add=<?= $product['id'] ?>">Add to Cart</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found in this category.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
