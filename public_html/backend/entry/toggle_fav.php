<?php
// public_html/backend/entry/toggle_fav.php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: /login.php');
  exit;
}
require __DIR__ . '/../db.php';

$uid    = $_SESSION['user_id'];
$eid    = intval($_GET['id'] ?? 0);
$action = $_GET['action'] === 'remove' ? 'remove' : 'add';

if ($action === 'add') {
  // Duplicate eklemeleri yoksay
  $stmt = $pdo->prepare("
    INSERT IGNORE INTO favorites (user_id, entry_id)
    VALUES (:u, :e)
  ");
  $stmt->execute(['u' => $uid, 'e' => $eid]);
} else {
  // Favoriyi sil
  $stmt = $pdo->prepare("
    DELETE FROM favorites
    WHERE user_id = :u AND entry_id = :e
  ");
  $stmt->execute(['u' => $uid, 'e' => $eid]);
}

// İşlemden sonra önceki sayfaya dönüp stari yeniden render etsin
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/entries.php'));
exit;
