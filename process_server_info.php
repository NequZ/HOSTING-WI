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
session_start();
if(!isset($_SESSION['username'])){
    header('location:index.php');
}
include 'configs/config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serverId = $_POST['serverid'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Password to be hashed
    $usableMemory = $_POST['usable_memory'];
    $usableCpu = $_POST['usable_cpu'];
    $usableDisk = $_POST['usable_disk'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL to insert server details
    $sql = "INSERT INTO nw_server_details (serverid, username, password, usable_memory, usable_cpu, usable_disk, current_used_memory, current_used_cpu, current_used_disk, online) VALUES (:serverid, :username, :password, :usable_memory, :usable_cpu, :usable_disk, 0, 0, 0, 0)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':serverid', $serverId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':usable_memory', $usableMemory);
        $stmt->bindParam(':usable_cpu', $usableCpu);
        $stmt->bindParam(':usable_disk', $usableDisk);

        $stmt->execute();

        // Redirect after successful insertion
        header('location:manage_server.php?serverid='.$serverId.'&success=Server details added successfully');
    } catch (PDOException $e) {
        // Handle errors and redirect
        header('location:manage_server.php?serverid='.$serverId.'&error='.urlencode($e->getMessage()));
    }
} else {
    // Redirect if the form is not submitted
    header('location:cluster.php');
}