<?php
include "../db.php";
include "ad-menu.php";

// ✅ Book ID validate
if (!isset($_GET['book_id']) || !is_numeric($_GET['book_id'])) {
    echo "<p class='text-danger'>Invalid Book ID.</p>";
    exit;
}

$book_id = (int)$_GET['book_id'];

// ✅ Fetch book details
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$book) {
    echo "<p class='text-danger'>Book not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Details - <?php echo htmlspecialchars($book['title']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>📖 Book Details</h2>
  <div class="row mt-4">
    <div class="col-md-4">
      <?php if(!empty($book['image'])): ?>
        <img src="../images/<?php echo htmlspecialchars($book['image']); ?>" class="img-fluid border" alt="Book Image">
      <?php else: ?>
        <p class="text-muted">No Image Available</p>
      <?php endif; ?>
    </div>
    <div class="col-md-8">
      <table class="table table-bordered">
        <tr>
          <th>Title</th>
          <td><?php echo htmlspecialchars($book['title']); ?></td>
        </tr>
        <tr>
          <th>Author</th>
          <td><?php echo htmlspecialchars($book['author']); ?></td>
        </tr>
        <tr>
          <th>ISBN</th>
          <td><?php echo htmlspecialchars($book['isbn']); ?></td>
        </tr>
        <tr>
          <th>Edition</th>
          <td><?php echo htmlspecialchars($book['edition']); ?></td>
        </tr>
        <tr>
          <th>Publication</th>
          <td><?php echo htmlspecialchars($book['publication']); ?></td>
        </tr>
        <tr>
          <th>Category</th>
          <td><?php echo htmlspecialchars($book['category']); ?></td>
        </tr>
        <tr>
          <th>Description</th>
          <td><?php echo htmlspecialchars($book['description']); ?></td>
        </tr>
        <tr>
          <th>Price</th>
          <td><?php echo htmlspecialchars($book['market_price']); ?></td>
        </tr>
        <tr>
          <th>Quantity</th>
          <td><?php echo htmlspecialchars($book['quantity']); ?></td>
        </tr>
        <tr>
          <th>Added Date</th>
          <td><?php echo htmlspecialchars($book['added_date']); ?></td>
        </tr>
      </table>
      <a href="books.php" class="btn btn-secondary mt-2">← Back to Books</a>
    </div>
  </div>
</div>
</body>
</html>
