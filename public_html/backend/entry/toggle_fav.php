<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: /login.php');
  exit;
}
require __DIR__ . '/../db.php';

$id     = intval($_GET['id'] ?? 0);
$action= $_GET['action'] ?? '';

if ($id && in_array($action, ['add','remove'])) {
  if ($action === 'add') {
    $sql = "INSERT IGNORE INTO favorites (user_id, entry_id) 
            VALUES (:u, :e)";
  } else {
    $sql = "DELETE FROM favorites 
            WHERE user_id = :u AND entry_id = :e";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['u'=>$_SESSION['user_id'], 'e'=>$id]);
}

header('Location: /entries.php');
exit;
