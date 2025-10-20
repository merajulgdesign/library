<?php
include "role_check.php";
include "ad-menu.php";
if (!isAdmin()) {
    die("❌ Access Denied! Only Admin can access this page.");
}

include "../db.php";

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

    if ($title === '' || $author === '') {
        $error = "Title এবং Author অবশ্যই দিতে হবে।";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Book image আপলোড করা হয়নি।";
    } else {
        $upload_dir = __DIR__ . '/../images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $orig_name = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($ext, $allowed)) {
            $error = "সাপোর্টেড ফাইল: jpg, jpeg, png, gif, webp.";
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $error = "ফাইল সাইজ 5MB এর বেশি হতে পারে না।";
        } else {
            $image_name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = $upload_dir . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $error = "Image আপলোড ব্যর্থ হয়েছে।";
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO books 
                    (title, author, description, category, isbn, edition, publication, market_price, quantity, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                if (!$stmt) {
                    $error = "❌ Prepare Failed: " . mysqli_error($conn);
                } else {
                    mysqli_stmt_bind_param($stmt, "ssssssssis",
                        $title, $author, $description, $category, $isbn,
                        $edition, $publication, $market_price, $quantity, $image_name
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        // Redirect to avoid resubmission
                        header("Location: add_book.php?success=1");
                        exit;
                    } else {
                        if (file_exists($dest)) @unlink($dest);
                        $error = "❌ Database Error: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}

// Check success message
if (isset($_GET['success'])) {
    $success = "✅ Book added successfully.";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add New Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container bg-white shadow p-4 rounded mt-4">
  <h2 class="mb-4">➕ Add New Book</h2>

  <?php if($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <?php if($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Author</label>
        <input class="form-control" name="author" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="3"></textarea>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <input class="form-control" name="category">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">ISBN</label>
        <input class="form-control" name="isbn">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Edition</label>
        <input class="form-control" name="edition">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Publication</label>
        <input class="form-control" name="publication">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Market Price</label>
        <input class="form-control" name="market_price" type="number" step="0.01">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" class="form-control" name="quantity" min="1" value="1">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Image</label>
      <input type="file" class="form-control" name="image" accept="image/*" required>
    </div>

    <div class="mt-3">
      <button class="btn btn-primary" type="submit">Add Book</button>
      <a href="books.php" class="btn btn-secondary">Back to List</a>
    </div>
  </form>
</div>
</body>
</html>
