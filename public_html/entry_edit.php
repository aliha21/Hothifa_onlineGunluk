<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: /login.php');
  exit;
}
require __DIR__ . '/backend/db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM entries WHERE id = :id AND user_id = :u");
$stmt->execute(['id'=>$id,'u'=>$_SESSION['user_id']]);
$e = $stmt->fetch() ?: exit('Girdi bulunamadı veya yetkiniz yok.');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Girdi Düzenle | <?= htmlspecialchars($e['title']) ?></title>
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
  <h1>Girdi Düzenle</h1>
  <form action="backend/entry/edit_process.php" method="post">
    <input type="hidden" name="id" value="<?= $e['id'] ?>">
    <label>
      Başlık:<br>
      <input type="text" name="title"
             value="<?= htmlspecialchars($e['title']) ?>" required>
    </label><br><br>
    <label>
      İçerik:<br>
      <textarea name="content" required><?= htmlspecialchars($e['content']) ?></textarea>
    </label><br><br>
    <button type="submit">Güncelle</button>
  </form>
  <p><a href="entries.php">← Günlüklere Dön</a></p>
</body>
</html>
