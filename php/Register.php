<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Database password
$dbname = "FIN_DB";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the submitted form data
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $role = 'user';  // Default role value (you can change this as needed)

        // Password validation (checking if both passwords match)
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!'); window.location.href = 'register.html';</script>";
            exit();
        }

        // Check if the user ID or email already exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id OR email = :email");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // User ID or email already exists
            echo "<script>alert('User ID or Email already exists. Please use a different one.'); window.location.href = 'register.html';</script>";
        } else {
            // Prepare SQL query to insert the new user, including role, created_on, and modified_on dates
            $stmt = $conn->prepare("INSERT INTO users (user_id, name, email, password, address, phone, created_on, modified_on,role) 
                                    VALUES (:user_id, :name, :email, :password, :address, :phone, NOW(), NOW(),:role)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);  // Storing password as plain text (not recommended)
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':role', $role);  // Bind the role field

            // Execute the query to insert the user
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now login.'); window.location.href = '../login.html';</script>";
            } else {
                echo "<script>alert('Error occurred during registration.'); window.location.href = 'register.html';</script>";
            }
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>