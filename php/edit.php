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

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $role = trim($_POST['role']);

        // Update user information in the database
        try {
            $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, address = :address, phone = :phone, role = :role WHERE user_id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $userId);

            if ($stmt->execute()) {
                $message = "User information updated successfully.";
            } else {
                $message = "Failed to update user information.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // Fetch user information
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $message = "User with ID $userId not found.";
            $user = [];
        }
    } catch (PDOException $e) {
        $message = "Error: " . htmlspecialchars($e->getMessage());
        $user = [];
    }
} else {
    $message = "Invalid user ID or ID not provided.";
    $user = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit User Information - SecureFuture Financials">
    <meta name="keywords" content="User, Edit, Information, Financials">
    <title>Edit User - SecureFuture Financials</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .edit-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            width: 70%;
        }

        .edit-form h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #004d99;
        }

        .edit-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .edit-form input[type="text"],
        .edit-form input[type="email"],
        .edit-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .edit-form button {
            background-color: #004d99;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .edit-form button:hover {
            background-color: #003366;
        }

        .message {
            color: #d9534f;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1>SecureFuture Financials</h1>
            <!-- Add navigation here if needed -->
        </div>
    </header>

    <main>
        <div class="container">
            <section class="edit-form">
                <h1>Edit User Information</h1>
                <?php if (isset($message)): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <form method="post" action="">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name"
                        value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>

                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address"
                        value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone"
                        value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>

                    <button type="submit">Save Changes</button>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SecureFuture Financials. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>