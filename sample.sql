

DROP DATABASE IF EXISTS ccbst;
CREATE DATABASE ccbst;
USE ccbst;


CREATE TABLE Customers(
    id int AUTO_INCREMENT PRIMARY KEY,
    phoneNumber bigint NOT NULL
);

CREATE TABLE Products(     -- One to many 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    price int NOT NULL,
    customer_id INT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customers(id)
);

CREATE TABLE Test(        -- One to One   
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL UNIQUE,
    FOREIGN KEY (customer_id) REFERENCES Customers(id)
); 

CREATE TABLE Sales(
    id INT AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(100) NOT NULL
);

CREATE TABLE Product_Sales(
    product_id int NOT NULL,
    sale_id int NOT NULL,
    PRIMARY KEY (product_id, sale_id),
    FOREIGN KEY (product_id) REFERENCES Products(id),
    FOREIGN KEY (sale_id) REFERENCES Sales(id)
);




-- Insert data into Customers table
INSERT INTO Customers (phoneNumber) VALUES (1234567890), (2345678901), (3456789012), (4567890123), (5678901234);

-- Insert data into Products table
INSERT INTO Products (price, customer_id) VALUES 
(100, 1), 
(200, 2), 
(300, 3), 
(400, 4), 
(500, 5);

-- Insert data into Test table (One-to-One relationship with Customers)
INSERT INTO Test (customer_id) VALUES 
(1), 
(2), 
(3), 
(4), 
(5);

-- Insert data into Sales table
INSERT INTO Sales (address) VALUES 
('123 Elm Street'), 
('456 Oak Avenue'), 
('789 Pine Road'), 
('321 Maple Lane'), 
('654 Birch Boulevard');

-- Insert data into Product_Sales table (Many-to-Many relationship between Products and Sales)
INSERT INTO Product_Sales (product_id, sale_id) VALUES 
(1, 1), 
(1, 2), 
(2, 2), 
(3, 3), 
(4, 4), 
(5, 5);