-- Create database if not exists
CREATE DATABASE IF NOT EXISTS mandamel;
USE mandamel;

-- Create users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    pronouns VARCHAR(10) NOT NULL,
    given_name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    country VARCHAR(100),
    city VARCHAR(100),
    postal_code VARCHAR(10),
    street VARCHAR(100),
    house_number VARCHAR(10),
    role ENUM('admin', 'employee', 'user') NOT NULL,
    user_state ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    newsletter TINYINT(1) NOT NULL DEFAULT 0,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME NULL
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);