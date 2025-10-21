<?php
session_start();
include "../db.php";
include "ad-menu.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$delete_id");
    header("Location: manage_users.php");
    exit();
}

// Handle search
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $search_safe = mysqli_real_escape_string($conn, $search);
    $users_res = mysqli_query($conn, "SELECT * FROM users 
        WHERE id LIKE '%$search_safe%' 
        OR name LIKE '%$search_safe%' 
        OR email LIKE '%$search_safe%' 
        OR role LIKE '%$search_safe%' 
        ORDER BY id DESC");
} else {
    $users_res = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4 class="mb-3">Manage Users</h4>

    <div class="d-flex justify-content-between mb-3">
        <!-- Search -->
        <form method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by ID, Name, Email or Role" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if ($search != ""): ?>
                <a href="manage_users.php" class="btn btn-secondary ms-2">Clear</a>
            <?php endif; ?>
        </form>

        <!-- Add User -->
        <a href="add_user.php" class="btn btn-success">+ Add User</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th><th>Name</th><th>Roll</th><th>Email</th><th>Role</th><th>Borrow Limit</th><th>Actions</th><th>Library Card</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($users_res) > 0): ?>
            <?php while($user = mysqli_fetch_assoc($users_res)): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['roll']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= htmlspecialchars($user['borrow_limit']) ?></td>
                 
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="manage_users.php?delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
                <td>
                <!-- ✅ Library Card বাটন -->
                <a href="library_card.php?user_id=<?= $user['id'] ?>" 
                   class="btn btn-sm btn-primary">
                   🪪 View Card
                </a>
            </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">No users found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
