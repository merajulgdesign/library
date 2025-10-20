<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    $user_id = (int)$_SESSION['user_id'];

    // ✅ Check if book exists
    $stmt = mysqli_prepare($conn, "SELECT quantity FROM books WHERE id=?");
    if (!$stmt) die("Book check prepare failed: " . mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "i", $book_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$book) {
        header("Location: borrowed_books.php?msg=Book not found&type=danger");
        exit();
    }

    if ((int)$book['quantity'] <= 0) {
        header("Location: borrowed_books.php?msg=Book out of stock&type=danger");
        exit();
    }

    // ✅ Check user borrow limit
    $stmt2 = mysqli_prepare($conn, "SELECT borrow_limit FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt2, "i", $user_id);
    mysqli_stmt_execute($stmt2);
    $res2 = mysqli_stmt_get_result($stmt2);
    $user = mysqli_fetch_assoc($res2);
    mysqli_stmt_close($stmt2);

    $borrow_limit = $user['borrow_limit'] ?? 3;

    $stmt3 = mysqli_prepare($conn, "SELECT COUNT(*) AS cnt FROM borrowed_books WHERE user_id=? AND status='borrowed'");
    mysqli_stmt_bind_param($stmt3, "i", $user_id);
    mysqli_stmt_execute($stmt3);
    $res3 = mysqli_stmt_get_result($stmt3);
    $count = mysqli_fetch_assoc($res3);
    mysqli_stmt_close($stmt3);

    if ($count['cnt'] >= $borrow_limit) {
        header("Location: borrowed_books.php?msg=Borrow limit reached&type=danger");
        exit();
    }

    // ✅ Insert borrow request (admin approval pending)
    $stmt4 = mysqli_prepare($conn, "INSERT INTO borrowed_books (user_id, book_id, status, return_status, borrow_date) VALUES (?, ?, 'pending', 'none', NOW())");
    if (!$stmt4) die("Insert prepare failed: " . mysqli_error($conn));
    mysqli_stmt_bind_param($stmt4, "ii", $user_id, $book_id);
    $success = mysqli_stmt_execute($stmt4);
    mysqli_stmt_close($stmt4);

    if ($success) {
        header("Location: borrowed_books.php?msg=Request sent to admin&type=success");
    } else {
        header("Location: borrowed_books.php?msg=Failed to send request&type=danger");
    }
    exit();

} else {
    header("Location: borrowed_books.php?msg=Invalid request&type=danger");
    exit();
}
?>
