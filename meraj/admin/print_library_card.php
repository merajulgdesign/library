<?php
include "../db.php";

$book_id = (int)($_GET['book_id'] ?? 0);

if ($book_id <= 0) {
    die("Invalid Book ID");
}

// fetch book info
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book) {
    die("Book not found");
}

// fetch borrower info (last borrowed)
$stmt2 = mysqli_prepare($conn, "SELECT u.name AS borrower_name, bb.due_date 
                                FROM borrowed_books bb
                                JOIN users u ON bb.user_id=u.id
                                WHERE bb.book_id=? AND bb.status='borrowed'
                                ORDER BY bb.borrow_date DESC LIMIT 1");
mysqli_stmt_bind_param($stmt2, "i", $book_id);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);
$borrower = mysqli_fetch_assoc($res2);
mysqli_stmt_close($stmt2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Library Card</title>
<style>
@media print {
    body { margin:0; padding:0; display:flex; justify-content:center; align-items:center; height:100vh; }
}
body { margin:0; padding:0; display:flex; justify-content:center; align-items:center; height:100vh; }
.card {
    width: 4in; 
    height: 6in; 
    padding:15px; 
    font-family: "Times New Roman", Times, serif; 
    box-sizing:border-box;
    border: 2px solid black;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.card h3, .card h4 { margin:5px 0; padding:0; text-align:center; }
.table { width:100%; border-collapse: collapse; margin-top: 10px; flex-grow:1; }
.table td, .table th { border: 1px solid black; height: 28px; text-align:center; vertical-align: middle; }
.footer { margin-top:10px; display:flex; justify-content: space-between; font-size:0.9rem; }
</style>
</head>
<body>
<div class="card">
    <!-- Book Info -->
    <div>
        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
        <h4>Author: <?php echo htmlspecialchars($book['author']); ?></h4>
        <h4>ISBN: <?php echo htmlspecialchars($book['isbn']); ?></h4>
    </div>

    <!-- Borrower Table -->
    <table class="table">
        <tr>
            <th>Due Date</th>
            <th>Borrower</th>
            <th>Signature</th>
        </tr>
        <?php
        // first row with borrower info if exists
        if ($borrower) {
            echo "<tr>
                    <td>".htmlspecialchars($borrower['due_date'])."</td>
                    <td>".htmlspecialchars($borrower['borrower_name'])."</td>
                    <td></td>
                  </tr>";
        } else {
            echo "<tr><td></td><td></td><td></td></tr>";
        }

        // remaining 9 empty rows
        for ($i=0; $i<9; $i++) {
            echo "<tr><td></td><td></td><td></td></tr>";
        }
        ?>
    </table>

    <!-- Librarian signature & seal -->
    <div class="footer">
        <div>Librarian: __________________</div>
        <div>Seal: __________________</div>
    </div>
</div>
<script>
window.print();
</script>
</body>
</html>
