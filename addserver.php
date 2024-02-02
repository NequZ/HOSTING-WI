<?php
session_start();
include 'configs/config.php'; 

function logServerAddition($pdo, $username, $action, $server_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO nw_log_server_add_cl (username, action, server_id) VALUES (:username, :action, :server_id)");
        
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':server_id', $server_id);
        
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Log insert failed: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = $_POST['servername'] ?? '';
    $serverip = $_POST['serverip'] ?? '';
    $active = $_POST['serveractive'] ?? '0';
    $created = date('Y-m-d H:i:s'); 

    // Validate input
    if (empty($servername) || empty($serverip)) {
        echo "Server name and IP are required.";
        exit;
    }

    $id = bin2hex(random_bytes(10)); 

    try {
        $stmt = $pdo->prepare("INSERT INTO nw_server (id, servername, ip, active, creation_date) VALUES (:id, :servername, :ip, :active, :created)");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':servername', $servername);
        $stmt->bindParam(':ip', $serverip);
        $stmt->bindParam(':active', $active);
        $stmt->bindParam(':created', $created);

        $stmt->execute();

        logServerAddition($pdo, $_SESSION['username'], "Server added to Cluster", $id);

        header('Location: cluster.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
