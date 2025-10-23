


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <?php include "inc/link-head.php";?>
    <link rel="stylesheet" href="css/style.css">
<style>
    .system_info{

    margin-top: 20px;
    background-color: #fff;
    text-align: center;
    font-family:'Times New Roman', Times, serif;
    border-radius: 20px;
    justify-content: center; /* horizontal center */
    align-items: center;     /* vertical center */
    border: 1px solid #000;
}
.outer-div {
      display: flex;               /* Flexbox */
      justify-content: center;     /* Horizontal center */
      align-items: center;         /* Vertical center */
      height: 450px;               /* Parent div height */
      background-color: #e4e7e9;
    }

    .inner-div {
        height: ;
      padding: 20px 30px;
      color: rgb(12, 1, 1);
      border-radius: 8px;
      text-align: center;
    }

</style>
</head>
<body>
    <?php "admin/role_check.php";?>
    <?php include "inc/navbar.php";?>
    <?php include "inc/menubar.php";?>
    <div class="container system_info border outer-div">
        <div class="inner-div">
            <h3> Project Title : Website Development of B.Sc (Hons)in CSE Program</h3>
            <h4>Library Management System</h4>
            <h4>Khanjahan Ali College Of Science & Technology</h4>
            
            
            <H6>GROUP-1</H6>
            <UL class="list-unstyled"><LI>
                <B>GROUP MEMBERS</B></LI>
                <LI>MD MERAJUL ISLAM</LI>
                <LI>AL FATTAH LABIB</LI>
                <LI>SUMAIYA AKTHER</LI>
            </UL>
        </div>
    </div>
   


    
    <footer>
        <?php include "inc/copyright.php";?>
    </footer>
</body>
</html>
