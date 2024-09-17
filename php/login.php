<?php
// Start the session to allow user data to persist across pages
session_start();

try {
    // Establish a connection to the MySQL database using PDO (PHP Data Objects)
    $conn = new PDO("mysql:host=localhost;dbname=fin_db", "root", "");
    // Set PDO error mode to exception to enable error reporting
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form was submitted using POST method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and store the submitted email and password from the POST request
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare an SQL statement to fetch user details based on email and password
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        // Bind the email and password to the query to prevent SQL injection
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        // Execute the query
        $stmt->execute();
        // Fetch the user data as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the query returned a user (i.e., the credentials are valid)
        if ($user) {
            // Set session variables with the user details for later use
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Store the user's role (admin or regular user) in the session

            // Redirect the user to the appropriate dashboard based on their role
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php'); // Admin users are redirected to the admin dashboard
            } else {
                header('Location: user_dashboard.php'); // Regular users are redirected to the user dashboard
            }
            exit(); // Ensure no further code is executed after the redirection
        } else {
            // If the email or password is incorrect, display an error message
            echo 'Invalid email or password!';
        }
    }
} catch (PDOException $e) {
    // If there is an error connecting to the database, display the error message
    echo "Connection failed: " . $e->getMessage();
}
?>
