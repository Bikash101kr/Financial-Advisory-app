<?php
session_start();
require 'db.php'; // Include your database configuration

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit();
}

// Fetch users from the database
try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data from the database
    $stmt = $conn->prepare("SELECT * FROM users"); // Adjust the table name and columns as needed
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log the error and display a user-friendly message
    error_log("Database connection error: " . $e->getMessage());
    echo "An error occurred while connecting to the database.";
    exit;
}
include '../admin_dashboard.html';

?>