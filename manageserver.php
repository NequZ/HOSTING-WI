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
if(isset($_GET['id'])){
    $serverId = $_GET['id'];

    // Prepare SQL to fetch server details
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
    // Redirect if no server ID is provided
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
                <h6>Manage Server - <?= htmlspecialchars($server['servername']) ?></h6>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Server ID</th>
                        <th>Server Name</th>
                        <th>Server IP</th>
                        <th>Creation Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= htmlspecialchars($server['id']) ?></td>
                        <td><?= htmlspecialchars($server['servername']) ?></td>
                        <td><?= htmlspecialchars($server['ip']) ?></td>
                        <td><?= htmlspecialchars($server['creation_date']) ?></td>
                        <td><?= $server['active'] ? 'Active' : 'Inactive' ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div></div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Server Details</h6>
            </div>
            <div class="card-body">
                <?php if ($serverDetails): ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Usable Memory</th>
                            <th>Current Used Memory</th>
                            <th>Usable CPU</th>
                            <th>Current Used CPU</th>
                            <th>Usable Disk</th>
                            <th>Current Used Disk</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= htmlspecialchars($serverDetails['usable_memory']) ?> GB</td>
                            <td><?= htmlspecialchars($serverDetails['current_used_memory']) ?> GB</td>
                            <td><?= htmlspecialchars($serverDetails['usable_cpu']) ?> Core/s</td>
                            <td><?= htmlspecialchars($serverDetails['current_used_cpu']) ?> Core/s</td>
                            <td><?= htmlspecialchars($serverDetails['usable_disk']) ?> GB</td>
                            <td><?= htmlspecialchars($serverDetails['current_used_disk']) ?> GB</td>
                            <td><?= $serverDetails['online'] ? 'Online' : 'Offline' ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-danger alert-dismissible text-white" role="alert">
                        <span class="text-sm">Warning, no Server Details found!<a href="setupserver_in_cluster.php?serverid=<?= $serverId ?>" class="text-white font-weight-bold"> Setup Server</a></span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>