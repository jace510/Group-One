CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    user_role ENUM('admin', 'seller', 'buyer') DEFAULT 'buyer'
);




CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
);




CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category INT,
    image VARCHAR(255),
    user_id INT,
    FOREIGN KEY (category) REFERENCES categories(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
