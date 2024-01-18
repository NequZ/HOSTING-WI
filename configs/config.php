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

// Debug Settings

$debug = true;
configureDebugMode($debug);

// Database Settings

$host = 'localhost';
$db   = 'nw_main'; // your database name
$user = 'nw_user'; // your database username
$pass = 'password'; // your database password
$charset = 'utf8mb4';

// Create database connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



function configureDebugMode($debug) {
    if ($debug) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}
