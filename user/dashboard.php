<?php
session_start();
include "../db.php";
include "anav.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$msg = $_GET['msg'] ?? '';
$msg_type = $_GET['type'] ?? 'info';

// get borrow_limit
$stmt = mysqli_prepare($conn, "SELECT borrow_limit FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

$borrow_limit = $user['borrow_limit'] ?? 3;

// current borrowed count
$stmt2 = mysqli_prepare($conn, "SELECT COUNT(*) AS cnt FROM borrowed_books WHERE user_id=? AND status='borrowed'");
mysqli_stmt_bind_param($stmt2, "i", $user_id);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);
$r2 = mysqli_fetch_assoc($res2);
$current_borrowed = (int)$r2['cnt'];
mysqli_stmt_close($stmt2);

// fetch books
$books_res = mysqli_query($conn, "SELECT * FROM books ORDER BY added_date DESC");

// fetch borrowed books
$borrowed_res = mysqli_query($conn, "
    SELECT bb.id AS borrow_id, b.title, b.author, b.isbn, bb.status, bb.return_status, bb.borrow_date
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    WHERE bb.user_id=$user_id
    ORDER BY bb.borrow_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="../dash.css">
</head>
<body>
<div class="container mt-4">
  <?php if($msg): ?>
    <div class="alert alert-<?php echo htmlspecialchars($msg_type); ?> alert-dismissible fade show">
      <?php echo htmlspecialchars($msg); ?>
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row mb-3">
    <div class="col-md-6">
      <div class="card p-3">
        <h5>Borrow Limit</h5>
        <p>Allowed: <strong><?php echo $borrow_limit; ?></strong></p>
        <p>Currently Borrowed: <strong><?php echo $current_borrowed; ?></strong></p>
         <a href="../admin/library_card.php?user_id" 
                   class="btn btn-sm btn-primary">
                   🪪 View Card </a>
      </div>
    </div>
  </div>

  <h4 class="mb-3">Available Books</h4>
  <div class="row g-4">
    <?php while ($book = mysqli_fetch_assoc($books_res)): ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100">
          <?php if (!empty($book['image']) && file_exists(__DIR__ . '/../images/' . $book['image'])): ?>
            <img src="../images/<?php echo htmlspecialchars($book['image']); ?>" class="card-img-top" style="height:180px;object-fit:cover;">
          <?php else: ?>
            <img src="../images/default.png" class="card-img-top" style="height:180px;object-fit:cover;">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h6 class="card-title mb-1"><?php echo htmlspecialchars($book['title']); ?></h6>
            <p class="text-muted mb-1 small"><?php echo htmlspecialchars($book['author']); ?></p>
            <p class="mb-2 small">ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
            <p class="mb-2"><strong>Available:</strong> <?php echo (int)$book['quantity']; ?></p>
            <div class="mt-auto">
              <?php if ((int)$book['quantity'] > 0 && $current_borrowed < $borrow_limit): ?>
                <form method="post" action="borrow.php">
                  <input type="hidden" name="book_id" value="<?php echo (int)$book['id']; ?>">
                  <button class="btn btn-primary w-100" type="submit" name="borrow">Borrow</button>
                </form>
              <?php elseif ($current_borrowed >= $borrow_limit): ?>
                <button class="btn btn-secondary w-100" disabled>Borrow Limit Reached</button>
              <?php else: ?>
                <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
