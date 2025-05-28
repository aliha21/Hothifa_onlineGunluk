<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require __DIR__ . '/backend/db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM entries WHERE id = :id AND user_id = :u");
$stmt->execute(['id' => $id, 'u' => $_SESSION['user_id']]);
$e = $stmt->fetch();

if (!$e) {
    exit('Girdi bulunamadı veya yetkiniz yok.');
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($e['title']) ?> | Görüntüle</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1><?= htmlspecialchars($e['title']) ?></h1>
  <p><small>Oluşturulma tarihi: <?= htmlspecialchars($e['created_at']) ?></small></p>
  <div class="entry-content">
    <?= nl2br(htmlspecialchars($e['content'])) ?>
  </div>
  <p>
    <a href="entries.php">← Günlüklere Dön</a> |
    <a href="entry_edit.php?id=<?= $e['id'] ?>">Düzenle</a> |
    <a href="backend/entry/delete_process.php?id=<?= $e['id'] ?>"
       onclick="return confirm('Bu girdi silinsin mi?')">Sil</a>
  </p>
</body>
</html>
