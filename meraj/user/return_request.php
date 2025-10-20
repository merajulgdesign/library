<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_id'])) {
    $borrow_id = (int)$_POST['borrow_id'];
    $user_id = (int)$_SESSION['user_id'];

    // Check if the borrow record belongs to the user and is still borrowed
    $check = mysqli_prepare($conn, "SELECT id FROM borrowed_books WHERE id=? AND user_id=? AND status='borrowed'");
    mysqli_stmt_bind_param($check, "ii", $borrow_id, $user_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) === 0) {
        $_SESSION['message'] = "Invalid request or already returned.";
        $_SESSION['message_type'] = "danger";
        header("Location: borrowed_books.php");
        exit();
    }
    mysqli_stmt_close($check);

    // Update status to return_requested
    $stmt = mysqli_prepare($conn, "UPDATE borrowed_books SET status='return_requested' WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($stmt, "ii", $borrow_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Return request sent successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to send return request.";
        $_SESSION['message_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
}

header("Location: borrowed_books.php");
exit();
?>
