<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
if (!isset($_GET['id'])) {
   header("Location: products.php");
   exit();
}
$id = (int)$_GET['id'];
// Get current data
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
   header("Location: products.php");
   exit();
}
// Get categories for dropdown
$categories = $pdo->query("SELECT id, category_name FROM categories ORDER BY category_name")->fetchAll();
// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $category_id = (int)$_POST['category_id'];
   $name = trim($_POST['product_name']);
   $price = $_POST['price'];
   $stock = (int)$_POST['stock'];
   $user_id = $_SESSION['user_id'];
   $stmt = $pdo->prepare("UPDATE products
                          SET category_id = ?, product_name = ?, price = ?, stock = ?, last_updated_by = ?
                          WHERE id = ?");
   $stmt->execute([$category_id, $name, $price, $stock, $user_id, $id]);
   header("Location: products.php?msg=updated");
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Edit Product</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="navbar">
       <div class="nav-left">
           <a href="products.php">← Back to Products</a>
       </div>
   </div>
   <div class="container">
       <h1>Edit Product (ID: <?= $product['id'] ?>)</h1>
       <div class="form-box">
           <form method="POST">
               <div class="form-group">
                   <label>Category</label>
                   <select name="category_id" required>
                       <?php foreach ($categories as $cat): ?>
                           <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                               <?= htmlspecialchars($cat['category_name']) ?>
                           </option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group">
                   <label>Product Name</label>
                   <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
               </div>
               <div class="form-row">
                   <div class="form-group">
                       <label>Price</label>
                       <input type="number" name="price" step="0.01" min="0" value="<?= $product['price'] ?>" required>
                   </div>
                   <div class="form-group">
                       <label>Stock</label>
                       <input type="number" name="stock" min="0" value="<?= $product['stock'] ?>" required>
                   </div>
               </div>
               <p><strong>Date Added:</strong> <?= $product['date_added'] ?></p>
               <p><strong>Last Updated:</strong> <?= $product['last_updated'] ?></p>
               <p><strong>Last Updated By:</strong> User ID <?= $product['last_updated_by'] ?></p>
               <button type="submit">Save Changes</button>
               <a href="products.php" class="btn-secondary">Cancel</a>
           </form>
       </div>
   </div>
</body>
</html>