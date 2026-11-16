CREATE DATABASE IF NOT EXISTS midterm_search;
USE midterm_search;
-- Users table (for login and last_updated_by)
CREATE TABLE users (
   id INT AUTO_INCREMENT PRIMARY KEY,
   username VARCHAR(50) NOT NULL UNIQUE,
   password VARCHAR(255) NOT NULL,
   fullname VARCHAR(100) NOT NULL
);
-- Parent Entity: categories
CREATE TABLE categories (
   id INT AUTO_INCREMENT PRIMARY KEY,
   category_name VARCHAR(100) NOT NULL,
   description TEXT,
   date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
   last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   last_updated_by INT,
   FOREIGN KEY (last_updated_by) REFERENCES users(id)
);
-- Children Entity: products
CREATE TABLE products (
   id INT AUTO_INCREMENT PRIMARY KEY,
   category_id INT NOT NULL,
   product_name VARCHAR(100) NOT NULL,
   price DECIMAL(10,2) NOT NULL,
   stock INT DEFAULT 0,
   date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
   last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   last_updated_by INT,
   FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
   FOREIGN KEY (last_updated_by) REFERENCES users(id)
);
-- Default admin account
-- Username: admin
-- Password: admin123
INSERT INTO users (username, password, fullname) VALUES
('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.rSo7EtXe.5jvpZRUYGzBy9qozPcthPu', 'Administrator');

