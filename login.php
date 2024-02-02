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


include 'configs/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM nw_users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($user = $stmt->fetch()) {
        if (password_verify($password, $user['password'])) {
            $updateStmt = $pdo->prepare("UPDATE nw_users SET online = 1, lastlogin = NOW() WHERE id = :id");
            $updateStmt->execute(['id' => $user['id']]);

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            logLoginAttempt($pdo, $username, 1); // 1 representing ACTION_LOGIN_SUCCESS

            header("Location: dashboard.php");
            exit;
        } else {
            logLoginAttempt($pdo, $username, 2); // 2 representing ACTION_LOGIN_FAIL_PASSWORD
            outputErrorMessage("Incorrect password. <a href='javascript:;' class='alert-link text-white'>Try again</a> or <a href='forgot_password.php' class='alert-link text-white'>reset your password</a>.");

        }
    } else {
        logLoginAttempt($pdo, $username, 3); // 3 representing ACTION_LOGIN_FAIL_USERNAME
        outputErrorMessage("Username not found. <a href='javascript:;' class='alert-link text-white'>Try again</a> or <a href='forgot_password.php' class='alert-link text-white'>reset your password</a>.");

    }
}


function outputErrorMessage($message) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <link rel='apple-touch-icon' sizes='76x76' href='../assets/img/apple-icon.png'>
        <link rel='icon' type='image/png' href='../assets/img/favicon.png'>
        <title>Material Dashboard 2 by Creative Tim</title>
        <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700' />
        <link href='../assets/css/nucleo-icons.css' rel='stylesheet' />
        <link href='../assets/css/nucleo-svg.css' rel='stylesheet' />
        <script src='https://kit.fontawesome.com/42d5adcbca.js' crossorigin='anonymous'></script>
        <link href='https://fonts.googleapis.com/icon?family=Material+Icons+Round' rel='stylesheet'>
        <link id='pagestyle' href='../assets/css/material-dashboard.css?v=3.1.0' rel='stylesheet' />
    </head>
    <body>
        <div class='card-body p-3 pb-0'>
            <div class='alert alert-primary alert-dismissible text-white' role='alert'>
                <span class='text-sm'>$message</span>
                <button type='button' class='btn-close text-lg py-3 opacity-10' data-bs-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000);
        </script>
    </body>
    </html>";
    exit;
}

function logLoginAttempt($pdo, $username, $action, $status = 1) {
    try {
        $stmt = $pdo->prepare("INSERT INTO nw_log_login_gl; (`user`, `action`, `status`) VALUES (:user, :action, :status)");
        $stmt->execute([
            ':user' => $username, 
            ':action' => $action,
            ':status' => $status
        ]);
    } catch (PDOException $e) {
        error_log("Failed to log login attempt: " . $e->getMessage());
    }
}

?>