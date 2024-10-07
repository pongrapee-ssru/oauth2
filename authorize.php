<?php
session_start();

// Assume that your database connection is set up in config.php with $conn as an output
require 'config.php';

// To make sure date/time functions are based on the server time
date_default_timezone_set("Asia/Bangkok");

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // Bind parameters
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate a secure token
        $token = bin2hex(random_bytes(32));
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['access_token'] = $token;

        // Set token expiration
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert token into database
        $insertTokenStmt = $conn->prepare("INSERT INTO access_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $insertTokenStmt->bind_param("iss", $user['id'], $token, $expiresAt);
        $insertTokenStmt->execute();

        // Redirect back with the token
        header('Location: ' . $_GET['redirect_uri'] . '?access_token=' . $token);
        exit();
    } else {
        // Handle login failure
        echo "Invalid username or password.";
    }
}
?>
