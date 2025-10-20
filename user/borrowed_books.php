<?php
session_start();
include "../db.php";
include "anav.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$msg = $_SESSION['message'] ?? '';
$msg_type = $_SESSION['message_type'] ?? 'info';
unset($_SESSION['message'], $_SESSION['message_type']);

// Fetch borrowed books
$stmt = mysqli_prepare($conn, "
    SELECT bb.id AS borrow_id, b.id AS book_id, b.title, b.author, bb.borrow_date, bb.due_date, bb.return_date, bb.status, bb.fine_amount
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    WHERE bb.user_id = ?
    ORDER BY bb.borrow_date DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$borrowed_books = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Fetch available books for borrow
$available_books_res = mysqli_query($conn, "SELECT * FROM books WHERE available_copies > 0 ORDER BY title ASC");
$available_books = mysqli_fetch_all($available_books_res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Borrowed Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.badge-status { font-size: 0.85rem; }
</style>
</head>
<body class="bg-light">
<div class="container mt-4">
<h4 class="mb-3 text-primary">📚 My Borrowed Books</h4>

<?php if($msg): ?>
<div class="alert alert-<?php echo htmlspecialchars($msg_type); ?> alert-dismissible fade show">
    <?php echo htmlspecialchars($msg); ?>
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Request New Book -->
<div class="mb-4">
<h5>📖 Request New Book</h5>
<?php if(empty($available_books)): ?>
<p class="text-muted">No books available for borrow.</p>
<?php else: ?>
<form method="POST" action="borrow_request.php" class="d-flex gap-2 flex-wrap">
    <select name="book_id" class="form-select w-auto">
        <?php foreach($available_books as $book): ?>
        <option value="<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title'].' ('.$book['author'].')'); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary">Request Borrow</button>
</form>

<?php endif; ?>
</div>

<!-- Borrowed Books Table -->
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover bg-white shadow-sm">
<thead class="table-dark text-center">
<tr>
<th>Title</th>
<th>Author</th>
<th>Borrow Date</th>
<th>Due Date</th>
<th>Return Date</th>
<th>Status</th>
<th>Fine</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php if(empty($borrowed_books)): ?>
<tr><td colspan="8" class="text-center py-3">No borrowed books found.</td></tr>
<?php else: ?>
<?php foreach($borrowed_books as $book): ?>
<tr class="text-center">
<td><?php echo htmlspecialchars($book['title']); ?></td>
<td><?php echo htmlspecialchars($book['author']); ?></td>
<td><?php echo $book['borrow_date'] ?: '-'; ?></td>
<td><?php echo $book['due_date'] ?: '-'; ?></td>
<td>
<?php
if($book['return_date']) {
    echo $book['return_date'];
} elseif($book['status'] === 'return_requested') {
    echo 'Pending Admin Approval';
} else {
    echo '-';
}
?>
</td>
<td>
<?php
switch ($book['status']) {
    case 'pending': echo "<span class='badge bg-warning text-dark badge-status'>Pending Approval</span>"; break;
    case 'borrowed': echo "<span class='badge bg-success badge-status'>Borrowed</span>"; break;
    case 'return_requested': echo "<span class='badge bg-primary badge-status'>Return Requested</span>"; break;
    case 'returned': echo "<span class='badge bg-info text-dark badge-status'>Returned</span>"; break;
    case 'rejected': echo "<span class='badge bg-danger badge-status'>Rejected</span>"; break;
}
?>
</td>
<td><?php echo $book['fine_amount'] > 0 ? '৳'.$book['fine_amount'] : '-'; ?></td>
<td>
<?php
if($book['status'] === 'borrowed') {
    echo '<form method="POST" action="return_request.php">
            <input type="hidden" name="borrow_id" value="'.$book['borrow_id'].'">
            <button type="submit" class="btn btn-sm btn-warning">Request Return</button>
          </form>';
} else {
    echo '<button class="btn btn-sm btn-secondary" disabled>—</button>';
}
?>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
