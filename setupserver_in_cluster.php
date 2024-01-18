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
if(isset($_GET['serverid'])){
    $serverId = $_GET['serverid'];

    $sql = "SELECT * FROM nw_server WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $serverId, PDO::PARAM_INT);
    $stmt->execute();

    $server = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$server) {
        // Handle case where server is not found
        echo "<p>Server not found.</p>";
        exit();
    }
} else {
    header('location:cluster.php?error=No server ID provided');
    exit();
}

$sqlDetails = "SELECT * FROM nw_server_details WHERE serverid = :serverid";
$stmtDetails = $pdo->prepare($sqlDetails);
$stmtDetails->bindParam(':serverid', $serverId, PDO::PARAM_INT);
$stmtDetails->execute();

$serverDetails = $stmtDetails->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Server Cluster - NequZ
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
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
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
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Cluster</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Cluster</h6>
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
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Create Server Information</h6>
            </div>
            <div class="card-body">
                <form action="process_server_info.php" method="post">
                    <input type="hidden" name="serverid" value="<?= htmlspecialchars($serverId) ?>">

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="usable_memory" class="form-label">Usable Memory (GB)</label>
                        <input type="number" class="form-control" id="usable_memory" name="usable_memory" required>
                    </div>

                    <div class="mb-3">
                        <label for="usable_cpu" class="form-label">Usable CPU (Cores)</label>
                        <input type="number" class="form-control" id="usable_cpu" name="usable_cpu" required>
                    </div>

                    <div class="mb-3">
                        <label for="usable_disk" class="form-label">Usable Disk Space (GB)</label>
                        <input type="number" class="form-control" id="usable_disk" name="usable_disk" required>
                    </div>
                    <div class="mb-3">
                        <div class="alert alert-warning alert-dismissible text-white" role="alert">
                            <span class="text-sm">Make sure you install the Hosting-WI Agent on your server. Otherwise the server will not be able to connect to the remote server.</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
