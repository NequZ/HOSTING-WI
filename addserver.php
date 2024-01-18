<?php
include 'configs/config.php'; // Include your database configuration file

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $servername = $_POST['servername'] ?? '';
    $serverip = $_POST['serverip'] ?? '';
    $active = $_POST['serveractive'] ?? '0'; // Default to '0' if not set
    $created = date('Y-m-d H:i:s'); // Default to current date and time if not set

    // Validate input
    if (empty($servername) || empty($serverip)) {
        // Handle the error - both server name and IP are required
        echo "Server name and IP are required.";
        exit;
    }

    // Generate a random ID
    $id = bin2hex(random_bytes(10)); // Generate a 20-character random ID

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO nw_server (id, servername, ip, active, creation_date) VALUES (:id, :servername, :ip, :active, :created)");

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':servername', $servername);
        $stmt->bindParam(':ip', $serverip);
        $stmt->bindParam(':active', $active);
        $stmt->bindParam(':created', $created);

        // Execute the statement
        $stmt->execute();

        // Redirect or inform of success
        echo "Server added successfully.";
        header('Location: cluster.php');
    } catch (PDOException $e) {
        // Handle SQL errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // Not a POST request
    echo "Invalid request.";
}
?>
