<?php
include "role_check.php";
include "../db.php";
include "ad-menu.php";

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: books.php"); exit; }

// Fetch book details
$res = mysqli_query($conn, "SELECT * FROM books WHERE id = $id");
$book = mysqli_fetch_assoc($res);
if (!$book) { header("Location: books.php"); exit; }

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $edition = trim($_POST['edition'] ?? '');
    $publication = trim($_POST['publication'] ?? '');
    $market_price = trim($_POST['market_price'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $image_name = $book['image'];

    // Handle new image if uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $orig = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($ext, $allowed)) {
            $error = "Invalid image type.";
        } else {
            $upload_dir = __DIR__ . '/../images/';
            $new_name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
                // Delete old image if exists
                if (!empty($image_name) && file_exists($upload_dir . $image_name)) {
                    @unlink($upload_dir . $image_name);
                }
                $image_name = $new_name;
            } else {
                $error = "Failed to upload new image.";
            }
        }
    }

    if (!$error) {
        $stmt = mysqli_prepare($conn, "UPDATE books 
            SET title=?, author=?, description=?, category=?, isbn=?, edition=?, publication=?, market_price=?, quantity=?, image=? 
            WHERE id=?");

        if (!$stmt) {
            $error = "SQL Prepare Failed: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "ssssssssisi",
                $title, $author, $description, $category, $isbn,
                $edition, $publication, $market_price, $quantity, $image_name, $id
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: books.php?success=Book+updated");
                exit;
            } else {
                $error = "Database Error: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container bg-white shadow p-4 rounded">
  <h2 class="mb-4">Edit Book</h2>

  <?php if($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Author</label>
        <input class="form-control" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($book['description']); ?></textarea>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <input class="form-control" name="category" value="<?php echo htmlspecialchars($book['category']); ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">ISBN</label>
        <input class="form-control" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Edition</label>
        <input class="form-control" name="edition" value="<?php echo htmlspecialchars($book['edition']); ?>">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Publication</label>
        <input class="form-control" name="publication" value="<?php echo htmlspecialchars($book['publication']); ?>">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Market Price</label>
        <input type="number" step="0.01" class="form-control" name="market_price" value="<?php echo htmlspecialchars($book['market_price']); ?>">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" class="form-control" name="quantity" value="<?php echo htmlspecialchars($book['quantity']); ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Image (leave empty to keep current)</label>
      <input type="file" class="form-control" name="image" accept="image/*">
      <?php if(!empty($book['image'])): ?>
        <div class="mt-2">
          <img src="../images/<?php echo htmlspecialchars($book['image']); ?>" width="120" class="border rounded">
        </div>
      <?php endif; ?>
    </div>

    <button class="btn btn-primary" type="submit">Update Book</button>
    <a href="books.php" class="btn btn-secondary">Back</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
