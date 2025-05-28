<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
require __DIR__ . '/../db.php';

$id      = intval($_POST['id'] ?? 0);
$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if (!$id || !$title || !$content) {
    exit('Eksik veri.');
}

$sql = "UPDATE entries
        SET title = :t, content = :c
        WHERE id = :id AND user_id = :u";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    't'   => $title,
    'c'   => $content,
    'id'  => $id,
    'u'   => $_SESSION['user_id']
]);

header('Location: /entries.php');
exit;
