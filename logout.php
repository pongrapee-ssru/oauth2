<?php
session_start();

// Assume that your database connection is set up in config.php with $conn as an output
require 'config.php';

if (isset($_SESSION['access_token'])) {
    // Remove the token from the database
    $stmt = $conn->prepare("DELETE FROM access_tokens WHERE token = ?");
    $stmt->bind_param("s", $_SESSION['access_token']);
    $stmt->execute();
}

session_destroy();
header('Location: login_form.html'); // Redirect after logout
exit();
?>
