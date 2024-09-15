<?php
session_start();
require 'db.php'; // Include your database configuration

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Get user ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'Invalid user ID.';
    exit();
}

$user_id = $_GET['id'];

try {
    // Fetch the user's current details
    $stmt = $conn->prepare("SELECT user_id, name, email, role FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo 'User not found.';
        exit();
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_role = $_POST['role'];

        // Update the user's role
        $updateStmt = $conn->prepare("UPDATE users SET role = :role WHERE user_id = :user_id");
        $updateStmt->bindParam(':role', $new_role);
        $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $updateStmt->execute();

        // Redirect to admin dashboard after successful update
        header('Location: admin_dashboard.php');
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Role - SecureFuture Financials</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .edit-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            width: 50%;
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
        }

        .edit-form button:hover {
            background-color: #003366;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.html"><img src="images/logo.png" alt="SecureFuture Financials Logo"></a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="php/login.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section class="edit-form">
            <h1>Edit User Role</h1>
            <form action="edit_role.php?id=<?php echo htmlspecialchars($user_id); ?>" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                    disabled>

                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    disabled>

                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>

                <button type="submit">Update Role</button>
            </form>
        </section>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Secure Future Financials. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>