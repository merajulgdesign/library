<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['return'])) {
    $record_id = (int)$_POST['record_id'];
    $user_id = (int)$_SESSION['user_id'];

    // চেক করো বইটা এই ইউজারের কিনা
    $check_stmt = mysqli_prepare($conn, "SELECT id, book_id, status FROM borrowed_books WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($check_stmt, "ii", $record_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $borrow_record = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check_stmt);

    if (!$borrow_record) {
        header("Location: borrowed_books.php?msg=Invalid record!&type=danger");
        exit();
    }

    if ($borrow_record['status'] === 'returned') {
        header("Location: borrowed_books.php?msg=Already returned!&type=warning");
        exit();
    }

    // এখন শুধু return_status pending করা হবে (admin approval বাকি)
    $update_stmt = mysqli_prepare($conn, "UPDATE borrowed_books SET return_status='pending' WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($update_stmt, "ii", $record_id, $user_id);
    $update_success = mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);

    if ($update_success) {
        header("Location: borrowed_books.php?msg=Return request sent to admin!&type=success");
    } else {
        header("Location: borrowed_books.php?msg=Failed to send return request!&type=danger");
    }
    exit();

} else {
    header("Location: borrowed_books.php?msg=Invalid access!&type=danger");
    exit();
}
?>
