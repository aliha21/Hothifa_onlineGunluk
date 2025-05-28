<?php
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
  <title>Yeni Girdi | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>+ Yeni Girdi Oluştur</h1>
  <form action="backend/entry/create_process.php" method="post">
    <label>
      Başlık:<br>
      <input type="text" name="title" required>
    </label><br><br>
    <label>
      İçerik:<br>
      <textarea name="content" rows="8" required></textarea>
    </label><br><br>
    <button type="submit">Kaydet</button>
  </form>
  <p><a href="entries.php">← Günlüklerime Dön</a></p>
</body>
</html>

