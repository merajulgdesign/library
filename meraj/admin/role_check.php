<?php
session_start();
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

// if you didn't login redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>