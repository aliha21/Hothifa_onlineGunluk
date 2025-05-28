<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
require __DIR__ . '/../db.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare(
      "DELETE FROM entries
       WHERE id = :id AND user_id = :u"
    );
    $stmt->execute([
      'id' => $id,
      'u'  => $_SESSION['user_id']
    ]);
}

header('Location: /entries.php');
exit;
