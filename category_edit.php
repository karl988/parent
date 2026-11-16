<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
if (!isset($_GET['id'])) {
   header("Location: categories.php");
   exit();
}
$id = (int)$_GET['id'];
// Get current data
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();
if (!$category) {
   header("Location: categories.php");
   exit();
}
// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $name = trim($_POST['category_name']);
   $desc = trim($_POST['description']);
   $user_id = $_SESSION['user_id'];
   $stmt = $pdo->prepare("UPDATE categories
                          SET category_name = ?, description = ?, last_updated_by = ?
                          WHERE id = ?");
   $stmt->execute([$name, $desc, $user_id, $id]);
   header("Location: categories.php?msg=updated");
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Edit Category</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="navbar">
       <div class="nav-left">
           <a href="categories.php">← Back to Categories</a>
       </div>
   </div>
   <div class="container">
       <h1>Edit Category (ID: <?= $category['id'] ?>)</h1>
       <div class="form-box">
           <form method="POST">
               <div class="form-group">
                   <label>Category Name</label>
                   <input type="text" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
               </div>
               <div class="form-group">
                   <label>Description</label>
                   <textarea name="description" rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
               </div>
               <p><strong>Date Added:</strong> <?= $category['date_added'] ?></p>
               <p><strong>Last Updated:</strong> <?= $category['last_updated'] ?></p>
               <p><strong>Last Updated By:</strong> User ID <?= $category['last_updated_by'] ?></p>
               <button type="submit">Save Changes</button>
               <a href="categories.php" class="btn-secondary">Cancel</a>
           </form>
       </div>
   </div>
</body>
</html>

