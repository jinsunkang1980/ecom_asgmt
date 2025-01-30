<?php 
include 'includes/header.php';
include 'includes/db.php';
?>

<style>
    .products-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr); /* 5 equal columns */
        gap: 20px; /* Space between products */
        margin: 20px auto;
        max-width: 1200px; /* Ensure container is centered and not too wide */
    }

    .products-heading {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        margin: 100px 0 20px 0;
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
        width: 100%; /* Ensure full width of the container */
        aspect-ratio: 5 / 8; /* Maintain 5x8 ratio */
        object-fit: cover; /* Cover the container while keeping the ratio */
        border-radius: 5px;
    }

    .product h3 {
        font-size: 1.2em;
        margin: 10px 0;
    }

    .product p.description {
        margin: 10px 0;
        font-size: 1em;
        min-height: 60px; /* Ensures consistent height for descriptions */
    }

    .product-footer {
        margin-top: auto; /* Pushes footer to the bottom */
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
        background-color:rgb(0, 1, 2);
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .product-footer a:hover {
        background-color:rgb(0, 4, 8);
    }
</style>

<div class="products-heading">Our Products</div>

<div class="products-container">
    <?php
    $result = $conn->query("SELECT * FROM products");
    while ($row = $result->fetch_assoc()):
    ?>
        <div class="product">
            <!-- Product Image -->
            <?php echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">' ?>
            
            <!-- Product Title -->
            <h3><?php echo $row['name']; ?></h3>
            
            <!-- Product Description -->
            <p class="description"><?php echo $row['description']; ?></p>
            
            <!-- Product Footer (Price and Button) -->
            <div class="product-footer">
                <p class="price">$<?php echo $row['price']; ?></p>
                <a href="cart.php?add=<?php echo $row['id']; ?>">Add to Cart</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
