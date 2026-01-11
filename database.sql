-- Create database
CREATE DATABASE IF NOT EXISTS quickshop;
USE quickshop;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Electronics', 'Electronic devices and accessories'),
('Clothing', 'Fashion and apparel'),
('Home & Kitchen', 'Home appliances and kitchen items'),
('Books', 'Books and publications');

-- Insert sample products
INSERT INTO products (category_id, name, description, price, stock, image, featured) VALUES
-- Electronics
(1, 'Smartphone X', 'Latest smartphone with advanced features', 699.99, 50, 'Smartphone X.avif', 1),
(1, 'Laptop Pro', 'High-performance laptop for professionals', 1299.99, 30, 'Laptop Pro.webp', 1),
(1, 'Action Camera', 'Capture your adventures in HD', 249.99, 40, 'Action Camera.jpeg', 0),
(1, 'Drone', 'Quadcopter drone with 4K camera', 499.99, 20, 'drone.jpg', 1),
(1, 'Gaming Headset', 'Immersive sound with noise cancellation', 79.99, 60, 'gaming-headset.jpg', 0),
(1, 'Mechanical Keyboard', 'Durable keyboard with tactile keys', 99.99, 50, 'mechanical-keyboard.jpg', 0),
(1, 'Wireless Mouse', 'Ergonomic mouse with fast response', 29.99, 70, 'wireless-mouse.jpg', 0),
(1, 'Portable Speaker', 'Bluetooth speaker with deep bass', 49.99, 80, 'portable-speaker.jpg', 0),
(1, 'Smart Hub', 'Control all smart devices from one hub', 129.99, 25, 'smart-hub.jpg', 1),
(1, 'External SSD', 'Fast storage drive for all devices', 149.99, 35, 'external-ssd.jpg', 0),
(1, 'Espresso Machine', 'Brew cafe-style espresso at home', 199.99, 15, 'espresso-machine.jpg', 1),

-- Clothing
(2, 'Designer T-Shirt', 'Comfortable cotton t-shirt', 29.99, 100, 'Designer T-Shirt.jpg', 1),
(2, 'Casual Sneakers', 'Stylish and comfortable sneakers', 59.99, 70, 'Casual Sneakers.jpeg', 1),
(2, 'Mens Hoodie', 'Warm and cozy hoodie', 49.99, 50, 'mens-hoodie.jpg', 0),
(2, 'Summer Dress', 'Lightweight and colorful dress', 39.99, 60, 'summer-dress.jpg', 0),
(2, 'Winter Jacket', 'Protective jacket for winter', 99.99, 40, 'winter-jacket.jpg', 1),
(2, 'Sport Socks', 'Comfortable socks for sports', 9.99, 120, 'sport-socks.jpg', 0),
(2, 'Womens Jeans', 'Classic denim jeans', 44.99, 70, 'womens-jeans.jpg', 0),

-- Home & Kitchen
(3, 'Coffee Maker', 'Automatic coffee maker with timer', 49.99, 25, 'Coffee Maker.webp', 1),
(3, 'Cookware Set', 'Non-stick pots and pans', 79.99, 30, 'cookware-set.jpg', 0),
(3, 'Desk Lamp', 'LED desk lamp with adjustable brightness', 29.99, 50, 'desk-lamp.jpg', 0),
(3, 'Robot Vacuum', 'Automatic cleaning robot for home', 249.99, 15, 'robot-vacuum.jpg', 1),
(3, 'Toaster Oven', 'Compact toaster oven with timer', 59.99, 25, 'toaster-oven.jpg', 0),
(3, 'Water Pitcher', 'Filtered water pitcher', 19.99, 40, 'water-pitcher.jpg', 0),

-- Books
(4, 'Art History', 'Explore famous art movements', 24.99, 50, 'art-history.jpg', 0),
(4, 'Childrens Storybook', 'Fun stories for kids', 14.99, 80, 'Children_s Storybook.jpeg', 0),
(4, 'Fantasy Novel', 'Epic fantasy adventure', 19.99, 60, 'fantasy-novel.jpg', 0),
(4, 'Sci-Fi Anthology', 'Collection of science fiction stories', 17.99, 40, 'scifi-anthology.jpg', 0),
(4, 'Self-Help Book', 'Improve yourself with practical tips', 12.99, 70, 'self-help.jpg', 0);
