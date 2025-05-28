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
  <title>+ Yeni Girdi Oluştur | Online Günlük</title>
  <link rel="stylesheet" href="/css/style.css">
  <!-- TinyMCE CDN (kendi API anahtarınla) -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.3/tinymce.min.js"></script>

  <script>
    tinymce.init({
      selector: 'textarea[name=content]',
      menubar: false,
      plugins: 'lists link',
      toolbar: 'bold italic underline | bullist numlist | link',
      branding: false,
      height: 300
    });
  </script>
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
      <textarea name="content" required></textarea>
    </label><br><br>
    <button type="submit">Kaydet</button>
  </form>
  <p><a href="entries.php">← Günlüklere Dön</a></p>
</body>
</html>
