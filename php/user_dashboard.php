<?php
session_start();
require 'db.php'; // Include your database configuration

// Ensure session email is set
if (!isset($_SESSION['email'])) {
    echo "User not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];

// Create a new PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user details from the database using email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $userEmail);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Fetch investment details (if applicable)
    $stmt = $conn->prepare("SELECT * FROM portfolio WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $investments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit;
    }

} catch (PDOException $e) {
    // Log the error and display a user-friendly message
    error_log("Database connection error: " . $e->getMessage());
    echo "An error occurred while connecting to the database.";
    exit;
}

include '../user_dashboard.html';
?>