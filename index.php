<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query to fetch user by email
    $query = $conn->prepare("SELECT id, full_name, role, password FROM Users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user's details
        $user = $result->fetch_assoc();

        // Verify the password using password_verify()
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on the user's role
            if ($user['role'] == 'Admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: user/user_dashboard.php");
            }
            exit();  // Important to stop further execution after the redirect
        } else {
            // Password is incorrect
            echo "Invalid email or password.";
        }
    } else {
        // User not found
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Asset Management</title>
    <link rel="stylesheet" href="assets/login.css"> <!-- Updated to point to the assets folder -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <!-- Display the logo -->
            <img src="assets/logo.png" alt="Logo" width="100" height="100"> <!-- Adjust size as needed -->
            <h1>Login</h1>
        </div>
        <form action="index.php" method="POST"> <!-- Ensure correct form submission path -->
            <input type="email" name="email" placeholder="Email" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <button type="submit" class="login-button">Login</button>
        </form>
        <a href="#" class="forgot-password">Forgot Password?</a>
    </div>
</body>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .login-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 300px;
    }
    .logo img {
        margin-bottom: 1rem;
    }
    .logo h1 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .input-field {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
    }
    .login-button {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
    }
    .login-button:hover {
        background-color: #0056b3;
    }
    .forgot-password {
        display: block;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: #007bff;
        text-decoration: none;
    }
    .forgot-password:hover {
        text-decoration: underline;
    }
</style>
</html>