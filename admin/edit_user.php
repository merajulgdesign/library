<?php

session_start();
include "../db.php";
include "ad-menu.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = (int)$_GET['id'];
$user_res = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($user_res);

if (!$user) {
    die("User not found!");
}

if (isset($_POST['update_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $borrow_limit = (int)$_POST['borrow_limit'];

    // If password field not empty, update password too
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET name='$name', email='$email', role='$role', borrow_limit='$borrow_limit', password='$password' WHERE id=$id";
    } else {
        $update_query = "UPDATE users SET name='$name', email='$email', role='$role', borrow_limit='$borrow_limit' WHERE id=$id";
    }

    mysqli_query($conn, $update_query);
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Edit User</h4>
    <form method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" required class="form-control" value="<?= htmlspecialchars($user['name']) ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="mb-3">
            <label>New Password (leave blank to keep old)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select">
                <option value="user" <?= $user['role']=='user'?'selected':''; ?>>User</option>
                <option value="admin" <?= $user['role']=='admin'?'selected':''; ?>>Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Borrow Limit</label>
            <input type="number" name="borrow_limit" class="form-control" value="<?= htmlspecialchars($user['borrow_limit']) ?>">
        </div>
        <button type="submit" name="update_user" class="btn btn-primary">Update</button>
        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
