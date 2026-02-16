-- SQLite Database Init Script for Members App
-- Run this to create all required tables

-- User Levels
CREATE TABLE IF NOT EXISTS trongate_user_levels (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    level_title VARCHAR(50) UNIQUE NOT NULL,
    level_description VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tokens
CREATE TABLE IF NOT EXISTS trongate_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    expires INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expiry_date INTEGER DEFAULT 0,
    date_created INTEGER DEFAULT 0,
    token_key VARCHAR(128)
);

-- Administrators
CREATE TABLE IF NOT EXISTS trongate_administrators (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_level_id INTEGER NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    suspended INTEGER DEFAULT 0,
    login_blocked_until INTEGER DEFAULT 0,
    failed_login_attempts INTEGER DEFAULT 0,
    failed_login_ip VARCHAR(50),
    last_failed_attempt INTEGER,
    last_login INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    active INTEGER DEFAULT 1
);

-- Users
CREATE TABLE IF NOT EXISTS trongate_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_level_id INTEGER NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    suspended INTEGER DEFAULT 0,
    login_blocked_until INTEGER DEFAULT 0,
    failed_login_attempts INTEGER DEFAULT 0,
    failed_login_ip VARCHAR(50),
    last_failed_attempt INTEGER,
    last_login INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    active INTEGER DEFAULT 1
);

-- Members
CREATE TABLE IF NOT EXISTS members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    trongate_user_id INTEGER
);

-- Insert default data
INSERT INTO trongate_user_levels (level_title, level_description) VALUES 
('admin', 'Administrator'),
('member', 'Member');

-- Admin user (password: admin123)
INSERT INTO trongate_administrators (user_level_id, username, password, email, first_name, last_name) VALUES 
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Admin', 'User');

-- Member user (password: admin123)
INSERT INTO members (username, email, password) VALUES 
('evan70', 'evan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
