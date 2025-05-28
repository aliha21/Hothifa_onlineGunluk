<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// PDO bağlantısını al
require __DIR__ . '/backend/db.php';

// Kullanıcının girdilerini ve favori durumunu çek
$sql = "
  SELECT 
    e.id,
    e.title,
    e.created_at,
    EXISTS(
      SELECT 1 
      FROM favorites f 
      WHERE f.user_id = e.user_id 
        AND f.entry_id = e.id
    ) AS is_fav
  FROM entries e
  WHERE e.user_id = :u
  ORDER BY e.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['u' => $_SESSION['user_id']]);
$entries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Günlüklerim | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Günlüklerim</h1>
  <p>
    <a href="entry_create.php">+ Yeni Girdi Oluştur</a> |
    <a href="favorites.php">⭐ Favoriler</a> |
    <a href="logout.php">Çıkış Yap</a>
  </p>

  <?php if (empty($entries)): ?>
    <p>Henüz hiç girdi yapmadınız.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($entries as $e): ?>
      <li>
        <strong><?= htmlspecialchars($e['title']) ?></strong>
        <small>(<?= htmlspecialchars($e['created_at']) ?>)</small><br>
        <a href="entry_edit.php?id=<?= $e['id'] ?>">Düzenle</a> |
        <a href="backend/entry/delete_process.php?id=<?= $e['id'] ?>"
           onclick="return confirm('Bu girdi silinsin mi?')">Sil</a> |
        <?php if ($e['is_fav']): ?>
          <a href="backend/entry/toggle_fav.php?id=<?= $e['id'] ?>&action=remove">⭐ Kaldır</a>
        <?php else: ?>
          <a href="backend/entry/toggle_fav.php?id=<?= $e['id'] ?>&action=add">☆ Ekle</a>
        <?php endif; ?>
          | <a href="entry_view.php?id=<?= $e['id'] ?>">Görüntüle</a>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
