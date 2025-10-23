<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ যদি অ্যাডমিন হয় তাহলে অন্য ইউজারের কার্ড দেখতে পারবে
if ($_SESSION['role'] === 'admin' && isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
} else {
    $user_id = (int)$_SESSION['user_id'];
}

// ✅ ইউজার ইনফো
$user_sql = "SELECT id, name, roll FROM users WHERE id=?";
$stmt = mysqli_prepare($conn, $user_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($user_result);
mysqli_stmt_close($stmt);

// ✅ ধার করা বই
$borrow_sql = "
    SELECT b.id AS book_id, b.title, b.author, bb.due_date, bb.return_date, bb.fine_amount 
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    WHERE bb.user_id=? 
    ORDER BY bb.borrow_date DESC
";
$stmt = mysqli_prepare($conn, $borrow_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$borrowed_books = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Library Card</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f8f9fa;
    font-family: "Times New Roman", serif;
}
.card-container {
    width: 750px;
    margin: 40px auto;
    background: white;
    border: 2px solid #000;
    padding: 20px 40px;
}
h4 {
    text-align: center;
    font-weight: bold;
}
.table th, .table td {
    border: 1px solid black !important;
    text-align: center;
    vertical-align: middle;
}
.print-btn {
    display: block;
    margin: 20px auto;
}
@media print {
    .print-btn, .navbar { display: none; }
    body { background: white; }
}
</style>
</head>
<body>

<nav class="navbar navbar-light bg-light shadow-sm">
    <div class="container">
         <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a class="navbar-brand fw-bold" href="manage_users.php">⬅ Back</a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
            <a class="navbar-brand fw-bold" href="../user/dashboard.php">⬅ Back</a>
             <?php endif; ?>
        <span class="fw-bold">Library Card</span>
    </div>
</nav>

<div class="card-container">
    <h4>Khan Jahan Ali College of Science & Technology</h4>
    <p class="text-center mb-2"><strong>Library Card</strong></p>

    <div class="mb-3">
        <b>Student ID:</b> <?= htmlspecialchars($user['id']) ?><br>
        <b>Name:</b> <?= htmlspecialchars($user['name']) ?><br>
        <b>Roll:</b> <?= htmlspecialchars($user['roll']) ?>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($borrowed_books)): ?>
                <tr><td colspan="6">No borrowed books yet.</td></tr>
            <?php else: ?>
                <?php foreach($borrowed_books as $b): ?>
                <tr>
                    <td><?= $b['book_id'] ?></td>
                    <td><?= htmlspecialchars($b['title']) ?></td>
                    <td><?= htmlspecialchars($b['author']) ?></td>
                    <td><?= $b['due_date'] ?? '-' ?></td>
                    <td><?= $b['return_date'] ?? '-' ?></td>
                    <td><?= $b['fine_amount'] ? '৳'.$b['fine_amount'] : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-5">
        <b>Librarian Signature</b>
    </div>
</div>

<button class="btn btn-primary print-btn" onclick="window.print()">🖨️ Print Card</button>

</body>
</html>
