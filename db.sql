DROP DATABASE IF EXISTS ccbst;

CREATE DATABASE ccbst;
USE ccbst;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
	id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
    
);

INSERT INTO ccbst.products (name, price, description, image) VALUES
('Black Mesh Monika Dress', 180.00, 'With a panelled corset-style top, that cinches your waist to create a beautifully structured outline, which then drapes, from the scalloped edging, into a gorgeous pleated tulle mesh skirt.', 'uploads/cloth1.jpeg'),
('Artemis Slip Skirt', 210, 'The Artemis Beading Slip Skirt, crafted from delicate Japanese nylon tulle, is the seasons ultimate Have to Have..', 'uploads/cloth2.jpeg'),
('Dominic Pinstripe Liam Vest', 190, 'This assortment serves as your WOFs (Without Fails) and are designed to create space in your wardrobe for creativity and experimentation..', 'uploads/cloth3.jpeg'),
('Dominic Pinstripe Reese Sculpted Trouser', 325.00, 'This assortment serves as your WOFs (Without Fails) and are designed to create space in your wardrobe for creativity and experimentation..', 'uploads/cloth4.jpeg'),
('Crispy Nylon Maxi Skirt', 230.00, 'This assortment serves as your WOFs (Without Fails) and are designed to create space in your wardrobe for creativity and experimentation.', 'uploads/cloth5.jpeg');

-- INSERT INTO ccbst.users (name, email, password) VALUES
-- ('admin', 'admin@admin.com', 'admin');