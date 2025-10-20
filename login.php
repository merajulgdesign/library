<?php
session_start();
include "db.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $login_type = $_POST['login_type']; // admin বা user
    $password = $_POST['password'];

    if ($login_type === "admin") {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "SELECT * FROM admin WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // ✅ Admin password check: supports plain or hash
            if ($password === $row['password'] || password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'admin';
                $_SESSION['name'] = $row['name'];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "❌ Password incorrect!";
            }
        } else {
            $error = "❌ No admin found with this email!";
        }

    } else { // user
        $roll = mysqli_real_escape_string($conn, $_POST['roll']);
        $sql = "SELECT * FROM users WHERE roll='$roll' AND role='user'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'user';
                $_SESSION['name'] = $row['name'];
                $_SESSION['roll'] = $row['roll'];
                header("Location: user/dashboard.php");
                exit();
            } else {
                $error = "❌ Password incorrect!";
            }
        } else {
            $error = "❌ No user found with this roll!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Library</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">
<div class="card shadow-lg border-0 rounded-4">
<div class="card-body p-4">
<h3 class="text-center mb-3">Login</h3>

<?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form action="" method="POST">
    <div class="mb-3">
        <label class="form-label">Login As</label>
        <select name="login_type" class="form-select" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <div class="mb-3 admin-email" style="display:none;">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3 user-roll">
        <label class="form-label">Roll Number</label>
        <input type="text" name="roll" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>

<p class="text-center mt-3 mb-0">
Don't have an account? <a href="register.php">Register</a>
</p>
</div>
</div>
</div>
</div>
</div>

<script>
const loginTypeSelect = document.querySelector('select[name="login_type"]');
const adminEmail = document.querySelector('.admin-email');
const userRoll = document.querySelector('.user-roll');

loginTypeSelect.addEventListener('change', function() {
    if (this.value === 'admin') {
        adminEmail.style.display = 'block';
        userRoll.style.display = 'none';
    } else {
        adminEmail.style.display = 'none';
        userRoll.style.display = 'block';
    }
});
</script>
</body>
</html>
