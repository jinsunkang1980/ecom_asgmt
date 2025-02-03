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

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);


ALTER TABLE products ADD COLUMN category_id INT;

ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user';

INSERT INTO categories (name) VALUES 
('Accessories'), 
('Tops'), 
('Bottoms'); 

INSERT INTO ccbst.products (name, price, description, image, category_id) VALUES
('Prada Aimée large leather shoulder bag', 4300.00, 'The sophisticated minimalism of the 90s inspires the new Prada Aimée bag, portrayed in the Fall/Winter 2024 Campaign.', 'uploads/bags2.jpg', 1),
('Medium Prada Galleria Saffiano leather bag', 6300, 'Production of the Prada Galleria bag, composed of 83 pieces, is an authentic fusion between industrial precision.', 'uploads/bags1.jpg', 1),
('Dominic Pinstripe Liam Vest', 190, 'This assortment serves as your WOFs (Without Fails) and are designed to create space in your wardrobe for creativity and experimentation..', 'uploads/cloth3.jpeg', 2),
('Dominic Pinstripe Reese Sculpted Trouser', 325.00, 'This assortment serves as your WOFs (Without Fails) and are designed to create space in your wardrobe for creativity and experimentation.', 'uploads/cloth4.jpeg', 2),
('Crispy Nylon Maxi Skirt', 230.00, 'This assortment serves as your WOFs (Without Fails) and are designed to create space in your wardrobe for creativity and experimentation.', 'uploads/cloth5.jpeg', 3);

INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@example.com', '$2y$10$msyr4B2I/MEXQnJyWtTPeOR.w73ccG6cjz0dLgYZxRBfjSE1P1F3q', 'admin');
