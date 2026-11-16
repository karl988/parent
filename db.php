<?php
$host = 'localhost';
$dbname = 'midterm_search';
$username = 'root';
$password = '';          // Change this if your MySQL has a password
try {
   $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
   die("Database connection failed: " . $e->getMessage());
}
?>