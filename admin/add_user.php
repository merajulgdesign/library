<?php
session_start();
include "../db.php";
include "ad-menu.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}


if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $borrow_limit = (int)$_POST['borrow_limit'];

    // Default role: user
    $role = "user";

    mysqli_query($conn, "INSERT INTO users (name, roll, email, password, role, borrow_limit) 
                         VALUES ('$name',$roll, '$email', '$password', '$role', '$borrow_limit')");
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Add New User</h4>
    <form method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" required class="form-control">
        </div>
           <div class="mb-3">
            <label>Roll</label>
            <input type="num" name="roll" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        
            <!-- 
                <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-select">
                <option value="user">User</option>
                 </select>
                </div>  -->
        <div class="mb-3">
            <label>Borrow Limit</label>
            <input type="number" name="borrow_limit" class="form-control" value="3">
        </div>
        <button type="submit" name="add_user" class="btn btn-success">Add User</button>
        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
