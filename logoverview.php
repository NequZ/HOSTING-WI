<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:index.php');
}
include 'configs/config.php';

// General Logs
try {
    $query = "SHOW TABLES LIKE '%_gl'";
    $stmt = $pdo->query($query);
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $tableCounts = [];

    foreach ($tables as $table) {
        $countQuery = "SELECT COUNT(*) FROM $table";
        $countStmt = $pdo->query($countQuery);
        $count = $countStmt->fetchColumn();
        $tableCounts[$table] = $count;
    }
} catch (PDOException $e) {
    error_log("Error accessing database: " . $e->getMessage());
}
// Server Logs
try {
    $query1 = "SHOW TABLES LIKE '%_cl'";
    $stmt1 = $pdo->query($query1);
    $tables1 = $stmt1->fetchAll(PDO::FETCH_COLUMN);

    $tableCounts1 = [];
    foreach ($tables1 as $table) {
        $countQuery1 = "SELECT COUNT(*) FROM $table";
        $countStmt1 = $pdo->query($countQuery1);
        $count1 = $countStmt1->fetchColumn();
        $tableCounts1[$table] = $count1;
    }
} catch (PDOException $e) {
    error_log("Error accessing database: " . $e->getMessage());
}
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
Created : 01.02.2024
Author  : NequZ

Copyright (c) 2024 NequZ. All rights reserved.
*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Dashboard - NequZ
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
</head>

<body class="g-sidenav-show  bg-gray-200">
<?php include 'configs/navbar.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Log - Overview</h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                </div>
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <a href="account.php" class="nav-link text-body font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">Logged in as <?= $_SESSION['username']; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid py-4">
    <div class="row">
        <!-- General Logs -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card shadow">
                <div class="card-header p-3 pt-2 bg-gradient-dark">
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize text-light">Overview</p>
                        <h4 class="mb-0 text-light">GENERAL LOGS</h4>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php foreach ($tableCounts as $tableName => $count): ?>
                        <p class="mb-0 pt-4 text-sm"><strong>Total Logs:</strong> <?= htmlspecialchars($count) ?></p>
                        <a href="view_general_logs.php" class="btn btn-primary mt-3">View Logs</a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Customer Logs -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card shadow">
                <div class="card-header p-3 pt-2 bg-gradient-dark">
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize text-light">Overview</p>
                        <h4 class="mb-0 text-light">CUSTOMER LOGS</h4>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php foreach ($tableCounts as $tableName => $count): ?>
                        <p class="mb-0 pt-4 text-sm"><strong>Total Logs:</strong> <?= htmlspecialchars($count) ?></p>
                        <a href="view_general_logs.php?" class="btn btn-primary mt-3">View Logs</a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
      <!-- Server Logs -->
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow">
                        <div class="card-header p-3 pt-2 bg-gradient-dark">
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize text-light">Overview</p>
                                <h4 class="mb-0 text-light">SERVER LOGS</h4>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <?php foreach ($tableCounts1 as $tableName1 => $count1): ?>
                                <p class="mb-0 pt-4 text-sm"><strong>Total Logs:</strong> <?= htmlspecialchars($count1) ?></p>
                                <a href="view_server_logs.php" class="btn btn-primary mt-3">View Logs</a>
                            <?php endforeach; ?>
                  </div>
            </div>
        </div>
    </div>
</div>

    <?php include 'configs/footer.php'; ?>
