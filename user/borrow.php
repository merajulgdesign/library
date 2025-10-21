<?php
session_start();
include "../db.php";

// ✅ শুধু user রা borrow করতে পারবে
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// ✅ যখন user কোনো বই borrow করতে চায়
if (isset($_POST['borrow'])) {
    $book_id = (int)$_POST['book_id'];
    $user_id = (int)$_SESSION['user_id'];

    // 1️⃣ বইটি আছে কিনা চেক
    $stmt = mysqli_prepare($conn, "SELECT available_copies FROM books WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $book_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$book) {
        header("Location: dashboard.php?msg=Book not found&type=danger");
        exit();
    }

    // 2️⃣ user আগেই ওই বই borrow করে ফেলেছে কিনা চেক
    $check = mysqli_prepare($conn, "SELECT id FROM borrowed_books WHERE user_id=? AND book_id=? AND status IN ('pending','borrowed')");
    mysqli_stmt_bind_param($check, "ii", $user_id, $book_id);
    mysqli_stmt_execute($check);
    $check_res = mysqli_stmt_get_result($check);
    if (mysqli_num_rows($check_res) > 0) {
        header("Location: dashboard.php?msg=You already requested or borrowed this book&type=warning");
        exit();
    }

    // 3️⃣ Borrow request তৈরি করো (pending অবস্থায়)
    $insert = mysqli_prepare($conn, "INSERT INTO borrowed_books (user_id, book_id, status, borrow_date) VALUES (?, ?, 'pending', NOW())");
    mysqli_stmt_bind_param($insert, "ii", $user_id, $book_id);
    $success = mysqli_stmt_execute($insert);
    mysqli_stmt_close($insert);

    if ($success) {
        header("Location: dashboard.php?msg=Borrow request sent to admin&type=success");
    } else {
        header("Location: dashboard.php?msg=Failed to send request&type=danger");
    }
    exit();
}

// ✅ Invalid access
header("Location: dashboard.php?msg=Invalid access&type=danger");
exit();
?>
