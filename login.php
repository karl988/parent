<?php
session_start();
require_once 'config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $username = trim($_POST['username']);
   $password = $_POST['password'];
   $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
   $stmt->execute([$username]);
   $user = $stmt->fetch();
   if ($user && password_verify($password, $user['password'])) {
       $_SESSION['user_id'] = $user['id'];
       $_SESSION['fullname'] = $user['fullname'];
       header("Location: index.php");
       exit();
   } else {
       $error = "Invalid username or password!";
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Login - Midterm Search Project</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="login-container">
       <h2>Login</h2>
       <?php if ($error): ?>
           <div class="alert error"><?= htmlspecialchars($error) ?></div>
       <?php endif; ?>
       <form method="POST">
           <div class="form-group">
               <label>Username</label>
               <input type="text" name="username" required>
           </div>
           <div class="form-group">
               <label>Password</label>
               <input type="password" name="password" required>
           </div>
           <button type="submit">Login</button>
       </form>
   </div>
</body>
</html>

