<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "library-db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
<?php
/*
INSERT INTO `users` (`id`, `name`, `Roll`, `email`, `password`, `role`, `created_at`) VALUES ('205027', 'Md. Merajul Islam', '120', 'mdmerajul.cse@gmail.com', '12345', 'admin', current_timestamp());



*/

?>