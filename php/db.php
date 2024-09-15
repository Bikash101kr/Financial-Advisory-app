<?php
$servername = "localhost";
$username = "root"; // Change if different
$password = ""; // Change if different
$dbname = "fin_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>