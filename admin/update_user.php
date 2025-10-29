<?php
session_start();
include "../db.php";

// ✅ Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['user_id'];
    $name = trim($_POST['name']);
    $roll = trim($_POST['roll']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $borrow_limit = (int)$_POST['borrow_limit'];

    $stmt = mysqli_prepare($conn,
        "UPDATE users SET name=?, roll=?, email=?, role=?, borrow_limit=? WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "ssssii",
        $name, $roll, $email, $role, $borrow_limit, $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: manage_users.php?msg=User Updated&type=success");
    } else {
        header("Location: manage_users.php?msg=Update Failed&type=danger");
    }
    exit(); 
}
?>
