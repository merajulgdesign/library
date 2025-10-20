<?php
include "role_check.php";
include "../db.php";
include "ad-menu.php";

if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit;
}

$id = (int) $_GET['id'];

// বই আছে কিনা চেক করা
$res = mysqli_query($conn, "SELECT image FROM books WHERE id = $id");
$book = mysqli_fetch_assoc($res);

if ($book) {
    // ছবি থাকলে delete করা
    $image_path = __DIR__ . "/../images/" . $book['image'];
    if (!empty($book['image']) && file_exists($image_path)) {
        @unlink($image_path);
    }

    // ডাটাবেস থেকে বই delete
    $delete = mysqli_query($conn, "DELETE FROM books WHERE id = $id");
    if ($delete) {
        header("Location: books.php?success=Book+deleted");
        exit;
    } else {
        header("Location: books.php?error=Failed+to+delete+book");
        exit;
    }
} else {
    header("Location: books.php?error=Book+not+found");
    exit;
}
?>
