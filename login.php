<?php
/*
________   _______   ________  ___  ___  ________
|\   ___  \|\  ___ \ |\   __  \|\  \|\  \|\_____  \
\ \  \\ \  \ \   __/|\ \  \|\  \ \  \\\  \\|___/  /|
\ \  \\ \  \ \  \_|/_\ \  \\\  \ \  \\\  \   /  / /
\ \  \\ \  \ \  \_|\ \ \  \\\  \ \  \\\  \ /  /_/__
\ \__\\ \__\ \_______\ \_____  \ \_______\\________\
\|__| \|__|\|_______|\|___| \__\|_______|\|_______|
\|__|

Project : NequZ - WI
Created : 16.01.2024
Author  : NequZ

Copyright (c) 2024 NequZ. All rights reserved.
*/


include 'configs/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM nw_users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($user = $stmt->fetch()) {
        // User found, now verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, update online status and last login time
            $updateStmt = $pdo->prepare("UPDATE nw_users SET online = 1, lastlogin = NOW() WHERE id = :id");
            $updateStmt->execute(['id' => $user['id']]);

            // Start session and set session variables
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to a logged-in page or dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Password is incorrect
            echo "Invalid username or password.";
        }
    } else {
        // No user found with that username
        echo "Invalid username or password.";
    }
}
?>