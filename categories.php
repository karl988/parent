<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
// Handle Delete
if (isset($_GET['delete'])) {
   $id = (int)$_GET['delete'];
   $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
   $stmt->execute([$id]);
   header("Location: categories.php?msg=deleted");
   exit();
}
// Handle Add New Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
   $name = trim($_POST['category_name']);
   $desc = trim($_POST['description']);
   $user_id = $_SESSION['user_id'];
   $stmt = $pdo->prepare("INSERT INTO categories (category_name, description, last_updated_by) VALUES (?, ?, ?)");
   $stmt->execute([$name, $desc, $user_id]);
   header("Location: categories.php?msg=added");
   exit();
}
// Search
$search = '';
$categories = [];
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
   $search = trim($_GET['search']);
   $stmt = $pdo->prepare("SELECT c.*, u.fullname AS updated_by_name
                          FROM categories c
                          LEFT JOIN users u ON c.last_updated_by = u.id
                          WHERE c.category_name LIKE ? OR c.description LIKE ?
                          ORDER BY c.id DESC");
   $stmt->execute(["%$search%", "%$search%"]);
   $categories = $stmt->fetchAll();
} else {
   $stmt = $pdo->query("SELECT c.*, u.fullname AS updated_by_name
                        FROM categories c
                        LEFT JOIN users u ON c.last_updated_by = u.id
                        ORDER BY c.id DESC");
   $categories = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Categories - Parent Entity</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="navbar">
       <div class="nav-left">
           <a href="index.php">Dashboard</a> |
           <strong>Categories (Parent)</strong>
       </div>
       <div class="nav-right">
           <a href="logout.php">Logout</a>
       </div>
   </div>
   <div class="container">
       <h1>Parent Entity: Categories</h1>
       <?php if (isset($_GET['msg'])): ?>
           <div class="alert success" id="alertBox">
               <?php
               if ($_GET['msg'] == 'added') echo "Category added successfully!";
               if ($_GET['msg'] == 'updated') echo "Category updated successfully!";
               if ($_GET['msg'] == 'deleted') echo "Category deleted successfully!";
               ?>
           </div>
       <?php endif; ?>
       <!-- Add Form -->
       <div class="form-box">
           <h3>Add New Category</h3>
           <form method="POST">
               <div class="form-group">
                   <label>Category Name</label>
                   <input type="text" name="category_name" required>
               </div>
               <div class="form-group">
                   <label>Description</label>
                   <textarea name="description" rows="2"></textarea>
               </div>
               <button type="submit" name="add_category">Add Category</button>
           </form>
       </div>
       <!-- Search -->
       <div class="search-box">
           <form method="GET">
               <input type="text" name="search" placeholder="Search category..." value="<?= htmlspecialchars($search) ?>">
               <button type="submit">Search</button>
               <?php if ($search): ?>
                   <a href="categories.php" class="btn-secondary">Clear</a>
               <?php endif; ?>
           </form>
       </div>
       <!-- Table -->
       <table>
           <thead>
               <tr>
                   <th>ID</th>
                   <th>Category Name</th>
                   <th>Description</th>
                   <th>Date Added</th>
                   <th>Last Updated</th>
                   <th>Updated By</th>
                   <th>Actions</th>
               </tr>
           </thead>
           <tbody>
               <?php if (count($categories) > 0): ?>
                   <?php foreach ($categories as $row): ?>
                       <tr>
                           <td><?= $row['id'] ?></td>
                           <td><?= htmlspecialchars($row['category_name']) ?></td>
                           <td><?= htmlspecialchars($row['description']) ?></td>
                           <td><?= $row['date_added'] ?></td>
                           <td><?= $row['last_updated'] ?></td>
                           <td><?= htmlspecialchars($row['updated_by_name'] ?? 'N/A') ?> (ID: <?= $row['last_updated_by'] ?>)</td>
                           <td>
                               <a href="category_edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                               <button class="btn-delete" onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['category_name'])) ?>')">
                                   Delete
                               </button>
                           </td>
                       </tr>
                   <?php endforeach; ?>
               <?php else: ?>
                   <tr>
                       <td colspan="7" style="text-align:center;">No records found.</td>
                   </tr>
               <?php endif; ?>
           </tbody>
       </table>
   </div>
   <script src="js/script.js"></script>
</body>
</html>

