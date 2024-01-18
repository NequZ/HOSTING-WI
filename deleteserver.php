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

if (!isset($_SESSION['username'])) {
    header('location:index.php');
    exit();
}

include 'configs/config.php';

if (isset($_GET['id'])) {
    $serverId = $_GET['id'];

    $sql = "DELETE FROM nw_server WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':id', $serverId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        header('location:cluster.php?message=Server deleted successfully');
    } else {
        header('location:cluster.php?error=Unable to delete server');
    }
} else {
    header('location:cluster.php?error=No server ID provided');
}


