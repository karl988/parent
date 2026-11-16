<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
// Handle Delete
if (isset($_GET['delete'])) {
   $id = (int)$_GET['delete'];
   $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
   $stmt->execute([$id]);
   header("Location: products.php?msg=deleted");
   exit();
}
// Handle Add New Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
   $category_id = (int)$_POST['category_id'];
   $name = trim($_POST['product_name']);
   $price = $_POST['price'];
   $stock = (int)$_POST['stock'];
   $user_id = $_SESSION['user_id'];
   $stmt = $pdo->prepare("INSERT INTO products (category_id, product_name, price, stock, last_updated_by)
                          VALUES (?, ?, ?, ?, ?)");
   $stmt->execute([$category_id, $name, $price, $stock, $user_id]);
   header("Location: products.php?msg=added");
   exit();
}
// Get all categories for the dropdown
$categories = $pdo->query("SELECT id, category_name FROM categories ORDER BY category_name")->fetchAll();
// Search
$search = '';
$products = [];
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
   $search = trim($_GET['search']);
   $stmt = $pdo->prepare("SELECT p.*, c.category_name, u.fullname AS updated_by_name
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id
                          LEFT JOIN users u ON p.last_updated_by = u.id
                          WHERE p.product_name LIKE ? OR c.category_name LIKE ?
                          ORDER BY p.id DESC");
   $stmt->execute(["%$search%", "%$search%"]);
   $products = $stmt->fetchAll();
} else {
   $stmt = $pdo->query("SELECT p.*, c.category_name, u.fullname AS updated_by_name
                        FROM products p
                        LEFT JOIN categories c ON p.category_id = c.id
                        LEFT JOIN users u ON p.last_updated_by = u.id
                        ORDER BY p.id DESC");
   $products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Products - Children Entity</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="navbar">
       <div class="nav-left">
           <a href="index.php">Dashboard</a> |
           <strong>Products (Children)</strong>
       </div>
       <div class="nav-right">
           <a href="logout.php">Logout</a>
       </div>
   </div>
   <div class="container">
       <h1>Children Entity: Products</h1>
       <?php if (isset($_GET['msg'])): ?>
           <div class="alert success" id="alertBox">
               <?php
               if ($_GET['msg'] == 'added') echo "Product added successfully!";
               if ($_GET['msg'] == 'updated') echo "Product updated successfully!";
               if ($_GET['msg'] == 'deleted') echo "Product deleted successfully!";
               ?>
           </div>
       <?php endif; ?>
       <!-- Add Form -->
       <div class="form-box">
           <h3>Add New Product</h3>
           <form method="POST">
               <div class="form-group">
                   <label>Category</label>
                   <select name="category_id" required>
                       <option value="">-- Select Category --</option>
                       <?php foreach ($categories as $cat): ?>
                           <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
               <div class="form-group">
                   <label>Product Name</label>
                   <input type="text" name="product_name" required>
               </div>
               <div class="form-row">
                   <div class="form-group">
                       <label>Price</label>
                       <input type="number" name="price" step="0.01" min="0" required>
                   </div>
                   <div class="form-group">
                       <label>Stock</label>
                       <input type="number" name="stock" min="0" value="0" required>
                   </div>
               </div>
               <button type="submit" name="add_product">Add Product</button>
           </form>
       </div>
       <!-- Search -->
       <div class="search-box">
           <form method="GET">
               <input type="text" name="search" placeholder="Search product or category..."
                      value="<?= htmlspecialchars($search) ?>">
               <button type="submit">Search</button>
               <?php if ($search): ?>
                   <a href="products.php" class="btn-secondary">Clear</a>
               <?php endif; ?>
           </form>
       </div>
       <!-- Table -->
       <table>
           <thead>
               <tr>
                   <th>ID</th>
                   <th>Product Name</th>
                   <th>Category</th>
                   <th>Price</th>
                   <th>Stock</th>
                   <th>Date Added</th>
                   <th>Last Updated</th>
                   <th>Updated By</th>
                   <th>Actions</th>
               </tr>
           </thead>
           <tbody>
               <?php if (count($products) > 0): ?>
                   <?php foreach ($products as $row): ?>
                       <tr>
                           <td><?= $row['id'] ?></td>
                           <td><?= htmlspecialchars($row['product_name']) ?></td>
                           <td><?= htmlspecialchars($row['category_name']) ?></td>
                           <td>₱<?= number_format($row['price'], 2) ?></td>
                           <td><?= $row['stock'] ?></td>
                           <td><?= $row['date_added'] ?></td>
                           <td><?= $row['last_updated'] ?></td>
                           <td><?= htmlspecialchars($row['updated_by_name'] ?? 'N/A') ?> (ID: <?= $row['last_updated_by'] ?>)</td>
                           <td>
                               <a href="product_edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                               <button class="btn-delete" onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['product_name'])) ?>')">
                                   Delete
                               </button>
                           </td>
                       </tr>
                   <?php endforeach; ?>
               <?php else: ?>
                   <tr>
                       <td colspan="9" style="text-align:center;">No records found.</td>
                   </tr>
               <?php endif; ?>
           </tbody>
       </table>
   </div>
   <script src="js/script.js"></script>
</body>
</html>

