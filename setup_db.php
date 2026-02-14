<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS parking_db");
    $pdo->exec("USE parking_db");

    // Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Slots Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS slots (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slot_number VARCHAR(10) UNIQUE NOT NULL,
        type ENUM('car', 'bike') DEFAULT 'car',
        status ENUM('available', 'occupied', 'reserved') DEFAULT 'available',
        price_per_hour DECIMAL(10,2) DEFAULT 20.00
    )");

    // Bookings Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        slot_id INT,
        vehicle_number VARCHAR(20) NOT NULL,
        entry_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        exit_time DATETIME NULL,
        total_amount DECIMAL(10,2) DEFAULT 0.00,
        status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (slot_id) REFERENCES slots(id)
    )");

    // Initial Data
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@park.com', '$adminPass', 'admin')");
    }

    // Seed Slots if empty
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM slots");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        for ($i = 1; $i <= 10; $i++) {
            $slotNum = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $pdo->exec("INSERT INTO slots (slot_number, type, price_per_hour) VALUES ('$slotNum', 'car', 50.00)");
        }
        for ($i = 1; $i <= 5; $i++) {
            $slotNum = 'B' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $pdo->exec("INSERT INTO slots (slot_number, type, price_per_hour) VALUES ('$slotNum', 'bike', 20.00)");
        }
    }

    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
