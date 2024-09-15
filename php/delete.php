<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Set a strong password for production
$dbname = "FIN_DB";

// Create a new PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}

// Check if 'id' is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = htmlspecialchars($_GET['id']); // Treat ID as a string

    // Delete user from the database
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id");
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            echo "<p>User with ID $userId has been successfully deleted.</p>";
            // Optionally redirect after successful deletion
            // header("Location: user_list.php"); // Redirect to user list or another page
            // exit;
        } else {
            echo "<p>Failed to delete user with ID $userId.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>Invalid user ID or ID not provided.</p>";
}
?>
