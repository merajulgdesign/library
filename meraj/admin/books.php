<?php
include "role_check.php";
include "ad-menu.php";
include "../db.php";


// 🧹 Delete book if requested
if (isset($_GET['delete']) && isAdmin()) {
    $id = (int) $_GET['delete'];

    $res = mysqli_query($conn, "SELECT image FROM books WHERE id = $id");
    $book = mysqli_fetch_assoc($res);

    if ($book) {
        $image_path = __DIR__ . "/../images/" . $book['image'];
        if (!empty($book['image']) && file_exists($image_path)) {
            @unlink($image_path);
        }
        mysqli_query($conn, "DELETE FROM books WHERE id=$id");
        header("Location: books.php?success=Book+deleted");
        exit;
    } else {
        header("Location: books.php?error=Book+not+found");
        exit;
    }
}

// Fetch books
$result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>All Books</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="">

<div class="container">
  <h2>All Books</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
  <?php endif; ?>

  <?php if(isAdmin()): ?>
    <a class="btn btn-primary mb-3" href="add_book.php">➕ Add New Book</a>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-light">
        <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Author</th>
          <th>Print Card</th>
          <th>Details</th>
          <?php if(isAdmin()): ?><th>Actions</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td>
            <?php if(!empty($row['image'])): ?>
              <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" width="60">
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['author']); ?></td>

          <!-- Print Library Card Button -->
          <td>
            <form action="print_library_card.php" method="get" target="_blank">
              <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-primary btn-sm">Print Card</button>
            </form>
          </td>

          <!-- Details Button -->
          <td>
            <form action="book_details.php" method="get" target="_blank">
              <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-info btn-sm">Details</button>
            </form>
          </td>

          <!-- Edit/Delete -->
          <?php if(isAdmin()): ?>
          <td>
            <a class="btn btn-sm btn-success" href="edit_book.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="books.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
          </td>
          <?php endif; ?>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
