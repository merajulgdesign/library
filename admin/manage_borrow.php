<?php
session_start();
include "../db.php";
include "ad-menu.php";

// ✅ Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$fine_per_day = 5; // প্রতিদিন ৫ টাকা ফাইন

// ✅ Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['borrow_id'])) {
    $borrow_id = (int)$_POST['borrow_id'];
    $action = $_POST['action'];

    // Borrow record fetch
    $res = mysqli_query($conn, "SELECT * FROM borrowed_books WHERE id=$borrow_id");
    $record = mysqli_fetch_assoc($res);

    if ($record) {
        $book_id = $record['book_id'];

        switch ($action) {
            case 'accept': // Borrow request accept
                if ($record['status'] === 'pending') {
                    $borrow_date = date('Y-m-d');
                    $due_date = date('Y-m-d', strtotime('+7 days'));
                    mysqli_query($conn, "UPDATE borrowed_books SET status='borrowed', borrow_date='$borrow_date', due_date='$due_date' WHERE id=$borrow_id");
                    mysqli_query($conn, "UPDATE books SET available_copies = available_copies - 1 WHERE id=$book_id AND available_copies > 0");
                    $msg = "✅ Borrow request accepted!";
                    $msg_type = "success";
                }
                break;

            case 'reject': // Borrow request reject
                if ($record['status'] === 'pending') {
                    mysqli_query($conn, "UPDATE borrowed_books SET status='rejected' WHERE id=$borrow_id");
                    $msg = "❌ Borrow request rejected!";
                    $msg_type = "danger";
                }
                break;

            case 'return_accept': // Return request accept
                if ($record['status'] === 'return_requested') {
                    $today = date('Y-m-d');
                    $due_date = $record['due_date'];
                    $fine = 0;
                    if ($today > $due_date) {
                        $diff = (strtotime($today) - strtotime($due_date)) / (60 * 60 * 24);
                        $fine = $diff * $fine_per_day;
                    }
                    mysqli_query($conn, "UPDATE borrowed_books SET status='returned', return_date='$today', fine_amount=$fine WHERE id=$borrow_id");
                    mysqli_query($conn, "UPDATE books SET available_copies = available_copies + 1 WHERE id=$book_id");
                    $msg = "📖 Book return accepted. Fine: ৳$fine";
                    $msg_type = "info";
                }
                break;

            case 'return_reject': // Return request reject
                if ($record['status'] === 'return_requested') {
                    mysqli_query($conn, "UPDATE borrowed_books SET status='borrowed' WHERE id=$borrow_id");
                    $msg = "🚫 Return request rejected!";
                    $msg_type = "warning";
                }
                break;
        }
    }
}

// ✅ Search feature
$search = $_GET['search'] ?? '';
$where = "";
if ($search != '') {
    $search = mysqli_real_escape_string($conn, $search);
    $where = "WHERE u.name LIKE '%$search%' OR u.roll LIKE '%$search%' OR b.title LIKE '%$search%' OR bb.status LIKE '%$search%'";
}

// ✅ Fetch all borrow records
$borrow_res = mysqli_query($conn, "
    SELECT bb.*, u.name AS user_name, u.roll, b.title AS book_title 
    FROM borrowed_books bb
    JOIN users u ON bb.user_id=u.id
    JOIN books b ON bb.book_id=b.id
    $where
    ORDER BY bb.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Borrow & Return | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .action-buttons { display: flex; gap: 5px; justify-content: center; flex-wrap: wrap; }
    .btn-sm { padding: 4px 8px; font-size: 0.8rem; }
</style>
</head>
<body class="bg-light">
<div class="container mt-4">
<h3 class="mb-3 text-center text-primary">📚 Manage Borrow & Return</h3>

<?php if(isset($msg)): ?>
<div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show text-center">
    <?php echo $msg; ?>
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Search -->
<form class="mb-3 d-flex justify-content-center" method="get">
    <input type="text" name="search" class="form-control w-50" placeholder="Search by user, roll, book or status" value="<?php echo htmlspecialchars($search); ?>">
    <button class="btn btn-primary ms-2">Search</button>
</form>

<!-- Borrow Table -->
<table class="table table-bordered table-hover bg-white shadow-sm text-center align-middle">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>User</th>
<th>Book</th>
<th>Borrow Date</th>
<th>Due Date</th>
<th>Return Date</th>
<th>Status</th>
<th>Fine</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($borrow_res) > 0): ?>
<?php while($row = mysqli_fetch_assoc($borrow_res)): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['user_name']); ?><br><small>(<?php echo $row['roll']; ?>)</small></td>
<td><?php echo htmlspecialchars($row['book_title']); ?></td>
<td><?php echo $row['borrow_date'] ?: '-'; ?></td>
<td><?php echo $row['due_date'] ?: '-'; ?></td>
<td><?php echo $row['return_date'] ?: '-'; ?></td>
<td>
<?php
switch ($row['status']) {
    case 'pending': echo "<span class='badge bg-warning text-dark'>Pending</span>"; break;
    case 'borrowed': echo "<span class='badge bg-success'>Borrowed</span>"; break;
    case 'return_requested': echo "<span class='badge bg-primary'>Return Requested</span>"; break;
    case 'returned': echo "<span class='badge bg-info text-dark'>Returned</span>"; break;
    case 'rejected': echo "<span class='badge bg-danger'>Rejected</span>"; break;
}
?>
</td>
<td><?php echo $row['fine_amount'] > 0 ? '৳'.$row['fine_amount'] : '-'; ?></td>
<td>
<form method="post" class="action-buttons">
    <input type="hidden" name="borrow_id" value="<?php echo $row['id']; ?>">

    <?php if ($row['status'] === 'pending'): ?>
        <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
    <?php elseif ($row['status'] === 'return_requested'): ?>
        <button type="submit" name="action" value="return_accept" class="btn btn-primary btn-sm">Accept Return</button>
        <button type="submit" name="action" value="return_reject" class="btn btn-warning btn-sm">Reject Return</button>
    <?php else: ?>
        <button class="btn btn-secondary btn-sm" disabled>—</button>
    <?php endif; ?>
</form>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="9" class="text-center text-muted py-3">No records found</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
