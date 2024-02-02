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

function logServerAction($pdo, $username, $action, $server_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO nw_log_server_add_cl (username, action, server_id) VALUES (:username, :action, :server_id)");
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':server_id', $server_id);
        
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Log insert failed: " . $e->getMessage());
    }
}

if (isset($_GET['id'])) {
    $serverId = $_GET['id'];

    $sql = "DELETE FROM nw_server WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':id', $serverId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        logServerAction($pdo, $_SESSION['username'], 'Server Deleted from Cluster', $serverId);

        header('location:cluster.php?message=Server deleted successfully');
        exit;
    } else {
        header('location:cluster.php?error=Unable to delete server');
        exit;
    }
} else {
    header('location:cluster.php?error=No server ID provided');
    exit;
}
