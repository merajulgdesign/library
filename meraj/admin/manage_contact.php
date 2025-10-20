<?php
session_start();
include "../db.php";
include "ad-menu.php";

// ✅ শুধুমাত্র অ্যাডমিন লগইন আছে কিনা চেক করা
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// ✅ Delete message
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM contact_messages WHERE id=$id");
    header("Location: manage_contact.php?msg=deleted");
    exit();
}

// ✅ Reply message
if (isset($_POST['reply_submit'])) {
    $msg_id = (int)$_POST['msg_id'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply_text']);
    mysqli_query($conn, "UPDATE contact_messages SET admin_reply='$reply', reply_date=NOW() WHERE id=$msg_id");
    header("Location: manage_contact.php?msg=replied");
    exit();
}

// ✅ Search / filter system
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM contact_messages WHERE 
            name LIKE '%$search%' OR 
            email LIKE '%$search%' OR 
            subject LIKE '%$search%' OR 
            roll LIKE '%$search%'
          ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Manage Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">


<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📩 Manage Contact Messages</h4>
            <form class="d-flex" method="get">
                <input type="text" name="search" class="form-control me-2" placeholder="Search name, roll, email..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-light btn-sm">Search</button>
            </form>
        </div>

        <div class="card-body">
            <?php if (isset($_GET['msg'])): ?>
                <?php if ($_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success">✅ Message deleted successfully!</div>
                <?php elseif ($_GET['msg'] == 'replied'): ?>
                    <div class="alert alert-success">💬 Reply sent successfully!</div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Reply</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="text-center"><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['roll']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['subject']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                    <td>
                                        <?php if (!empty($row['admin_reply'])): ?>
                                            <div class="border p-2 bg-light"><?= nl2br(htmlspecialchars($row['admin_reply'])) ?></div>
                                            <small class="text-muted">🕒 <?= $row['reply_date'] ?></small>
                                        <?php else: ?>
                                            <form method="post" class="mt-2">
                                                <input type="hidden" name="msg_id" value="<?= $row['id'] ?>">
                                                <textarea name="reply_text" class="form-control mb-2" rows="2" placeholder="Type your reply..." required></textarea>
                                                <button type="submit" name="reply_submit" class="btn btn-success btn-sm">Reply</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $row['created_at'] ?></td>
                                    <td class="text-center">
                                        <a href="?delete=<?= $row['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this message?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center text-muted">No messages found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-4 text-muted">
    &copy; <?= date('Y') ?> Library Management System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
