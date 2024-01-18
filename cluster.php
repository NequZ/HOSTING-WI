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
$servers = array();
$sql = "SELECT * FROM nw_server";

$stmt = $pdo->prepare($sql);

$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $servers[] = $row;
}

$stmt = null;

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
    <!-- Server Table -->
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Server List</h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServerModal">
                    Add New Server
                </button>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Server ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Server Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Server IP</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Creation Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Active</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($servers as $server): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($server['id']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($server['servername']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($server['ip']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($server['creation_date']) ?>
                                </td>
                                <td>
                                    <?php if ($server['active'] == 1): ?>
                                        <span class="badge badge-sm bg-gradient-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-sm bg-gradient-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <a href="manageserver.php?id=<?= htmlspecialchars($server['id']) ?>" class="text-secondary font-weight-bold text-s" data-toggle="tooltip" data-original-title="Manage Server">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    &nbsp; <!-- Spacer -->
                                    <a href="deleteserver.php?id=<?= htmlspecialchars($server['id']) ?>" class="text-secondary font-weight-bold text-s" data-toggle="tooltip" data-original-title="Delete Server">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Server Modal -->
    <div class="modal fade" id="addServerModal" tabindex="-1" aria-labelledby="addServerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServerModalLabel">Add New Server</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="addserver.php" method="post">
                    <div class="modal-body">
                        <!-- Server Name Input -->
                        <div class="mb-3">
                            <label for="servername" class="form-label">Server Name</label>
                            <input type="text" class="form-control" id="servername" name="servername" required>
                        </div>
                        <!-- Server IP Input -->
                        <div class="mb-3">
                            <label for="serverip" class="form-label">Server IP</label>
                            <input type="text" class="form-control" id="serverip" name="serverip" required>
                        </div>
                        <!-- Server Active Status -->
                        <div class="mb-3">
                            <label for="serveractive" class="form-label">Active</label>
                            <select class="form-select" id="serveractive" name="serveractive">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Server</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>