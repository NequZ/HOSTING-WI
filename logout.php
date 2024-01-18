<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:index.php');
}
include 'configs/config.php';
configureDebugMode($debug);

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
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Prepare and execute the SELECT statement
    $selectStmt = $pdo->prepare("SELECT * FROM nw_users WHERE username = :username");
    $selectStmt->execute(['username' => $username]);

    // Fetch the result
    $row = $selectStmt->fetch();

    // Prepare and execute the UPDATE statement
    $updateStmt = $pdo->prepare("UPDATE nw_users SET online = :online WHERE username = :username");
    $updateStmt->execute(['online' => 0, 'username' => $username]);
    header ('location: index.php');
} else {

    ?>
<div class="card-body p-3 pb-0">
    <div class="alert alert-primary alert-dismissible text-white" role="alert">
        <span class="text-sm">A simple primary alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<?php

}

// Destroy the session
session_destroy();