<?php
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Dashboard</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="navbar">
       <div class="nav-left">
           <strong>Midterm Search Project</strong>
       </div>
       <div class="nav-right">
           Welcome, <?= htmlspecialchars($_SESSION['fullname']) ?> |
           <a href="logout.php">Logout</a>
       </div>
   </div>
   <div class="container">
       <h1>Dashboard</h1>
       <div class="card-grid">
           <a href="categories.php" class="card">
               <h3>Parent Entity</h3>
               <p>Manage Categories<br>(Search / Update / Delete)</p>
           </a>
           <a href="products.php" class="card">
               <h3>Children Entity</h3>
               <p>Manage Products<br>(Search / Update / Delete)</p>
           </a>
       </div>
   </div>
</body>
</html>

