<?php
// public_html/index.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Günlüklerim | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Hoş geldin, <?= htmlspecialchars($_SESSION['username']) ?></h1>
  <p><a href="logout.php">Çıkış Yap</a></p>
  <!-- Buraya CRUD formları ve günlük listesi gelecek -->
</body>
</html>
