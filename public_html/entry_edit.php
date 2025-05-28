<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
require __DIR__ . '/backend/db.php';

// Gelen ID’yi al ve kullanıcının girdisini çek
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT * FROM entries WHERE id = :id AND user_id = :u"
);
$stmt->execute([
    'id' => $id,
    'u'  => $_SESSION['user_id']
]);
$e = $stmt->fetch();
if (!$e) {
    exit('Girdi bulunamadı veya yetkiniz yok.');
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Düzenle: <?= htmlspecialchars($e['title']) ?></title>
  <link rel="stylesheet" href="css/style.css">
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
      <textarea name="content" rows="8" required><?= htmlspecialchars($e['content']) ?></textarea>
    </label><br><br>
    <button type="submit">Güncelle</button>
  </form>
  <p><a href="entries.php">← Günlüklerime Dön</a></p>
</body>
</html>
