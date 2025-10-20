<?php
session_start();
include "../db.php";
include "ad-menu.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all books
$books_res = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM books WHERE id=$delete_id");
    header("Location: manage_books.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-4">
<h4>All Books</h4>
<a href="add_book.php" class="btn btn-success mb-3">Add New Book</a>
<table class="table table-bordered table-striped">
<thead class="table-light">
<tr>
<th>ID</th>
<th>Title</th>
<th>Author</th>
<th>ISBN</th>
<th>Quantity</th>
<th>Available</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($book = mysqli_fetch_assoc($books_res)): ?>
<tr>
<td><?= $book['id'] ?></td>
<td><?= htmlspecialchars($book['title']) ?></td>
<td><?= htmlspecialchars($book['author']) ?></td>
<td><?= htmlspecialchars($book['isbn']) ?></td>
<td><?= $book['quantity'] ?></td>
<td><?= $book['available_copies'] ?></td>
<td>
<a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
<a href="manage_books.php?delete=<?= $book['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
