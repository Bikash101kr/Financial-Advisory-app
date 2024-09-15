<?php
session_start();
require 'db.php'; // Include your database configuration

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch users from the database
try {
    $stmt = $conn->query("SELECT id, first_name, last_name, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching users: " . $e->getMessage();
    exit();
}
?>