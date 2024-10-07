<?php
session_start();

// Assume that your database connection is set up in config.php with $conn as an output
require 'config.php';

// To make sure date/time functions are based on the server time
date_default_timezone_set("Asia/Bangkok");

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['access_token'])) {
    header('Location: login_form.html'); // Redirect to login if not logged in
    exit();
}

// Validate the access token
$accessToken = $_GET['access_token'];
$stmt = $conn->prepare("SELECT * FROM access_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $accessToken);
$stmt->execute();
$result = $stmt->get_result();
$tokenData = $result->fetch_assoc();

if (!$tokenData) {
    // Invalid or expired token
    header('Location: login_form.html'); // Redirect to login
    exit();
}

// Your protected content
echo "Welcome to the protected page!";

// Logout button
echo '<form action="logout.php" method="POST"><button type="submit">Logout</button></form>';
?>
