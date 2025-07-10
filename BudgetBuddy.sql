CREATE DATABASE IF NOT EXISTS budgetbuddy;
USE budgetbuddy;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255) NULL,
    type ENUM('income', 'expense') NOT NULL
);

INSERT INTO categories (name, description, type) VALUES
('Food', 'Daily meals, snacks, drinks', 'expense'),
('Transport', 'Public transport, petrol, parking', 'expense'),
('Accommodation', 'Rent, utilities, maintenance', 'expense'),
('Tuition', 'University tuition fees and related expenses', 'expense'),
('Books & Supplies', 'Textbooks, stationery, academic materials', 'expense'),
('Entertainment', 'Movies, games, social activities', 'expense'),
('Health', 'Medical, dental, pharmacy expenses', 'expense'),
('Personal Care', 'Haircuts, skincare, toiletries', 'expense'),
('Savings', 'Money set aside for future use', 'income'),
('Salary', 'Part-time job or freelance income', 'income'),
('Allowance', 'Allowance from parents or guardians', 'income'),
('Miscellaneous', 'Other uncategorized expenses or income', 'expense');


CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    target_amount DECIMAL(10,2) NOT NULL,
    saved_amount DECIMAL(10,2) DEFAULT 0,
    deadline DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE suggestions (
    suggestion_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    content VARCHAR(255) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);
