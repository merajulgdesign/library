<?php
session_start();
include "../db.php";
include "ad-menu.php";
// admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/dash.css">
</head>
<body>

<div class="d-flex">
  

  <div class=" dashboard col-lg-2 col-md-2 col-sm-12 col-xs-12 g-5">
   
      <a href="add_book.php" class="   ">
        <h5 class="sidebar">Add Book</h5>
      </a>
    
          <a href="edit_book.php" class="  ">
        <h5 class="sidebar">Edit Book</h5>
      </a>
      
          <a href="add_user.php" class=" ">
        <h5 class="sidebar">Add User</h5>
      </a>
  
          <a href="manage_users.php" class="">
            <h5 class="sidebar">Edit User</h5>
      </a>
      
          <a href="manage_users.php" class="">
        <h5 class="sidebar">Manage Users</h5>
      </a>
      
      <a href="manage_books.php" class="">
        <h5 class="sidebar">Manage Books</h5>
      </a>
      
    
      <a href="manage_borrow.php" class="">
        <h5 class="sidebar">Manage Returns</h5>
      </a>
 
      <a href="manage_borrow.php" class="">
        <h5 class="sidebar">Manage Borrow</h5>
      </a>
    
      <a href="books.php" class="">
        <h5 class="sidebar">Print Library Cards</h5>
      </a>
    </div>
      <div class="adwelcome offset-2 col-lg-6 col-md-6 col-sm-12 col-xs-12 g-5">
        <div>
       <h3 class=" mb-4">Welcome, Admin!  </h3>
       <p>Click any button to manage Users, Books, Returns, or add a new book.</p>
        </div>
     
      <div class="mt-5">
        
      </div>
      </div>
  
</div>
<?php include "../inc/copyright.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
