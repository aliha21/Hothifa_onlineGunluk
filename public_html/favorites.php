<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
require __DIR__ . '/backend/db.php';

// Kullanıcının favoride işaretlediği girdileri çek
$sql = "
  SELECT e.id, e.title, e.created_at
  FROM entries e
  JOIN favorites f 
    ON f.entry_id = e.id
  WHERE f.user_id = :u
  ORDER BY f.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['u' => $_SESSION['user_id']]);
$favs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Favorilerim | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>⭐ Favorilerim</h1>
  <p>
    <a href="entries.php">← Günlüklere Dön</a> |
    <a href="logout.php">Çıkış Yap</a>
  </p>

  <?php if (empty($favs)): ?>
    <p>Henüz hiç favori girdiniz yok.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($favs as $e): ?>
      <li>
        <strong><?= htmlspecialchars($e['title']) ?></strong>
        <small>(<?= htmlspecialchars($e['created_at']) ?>)</small>
        — <a href="backend/entry/toggle_fav.php?id=<?= $e['id'] ?>&action=remove">
            ⭐ Kaldır
          </a>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
