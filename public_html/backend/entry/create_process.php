<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
require __DIR__ . '/../db.php';

$title   = trim($_POST['title']   ?? '');
$content = trim($_POST['content'] ?? '');

if (!$title || !$content) {
    exit('Lütfen başlık ve içerik girin.');
}

$sql = "INSERT INTO entries (user_id, title, content)
        VALUES (:u, :t, :c)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'u' => $_SESSION['user_id'],
    't' => $title,
    'c' => $content
]);

header('Location: /entries.php');
exit;
