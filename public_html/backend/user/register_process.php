<?php
// public_html/backend/user/register_process.php
require __DIR__ . '/../db.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    exit('Lütfen tüm alanları doldurun.');
}

// Şifreyi güvenli şekilde hash’le
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES (:u, :p)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['u' => $username, 'p' => $hash]);
    // Kayıt başarılı, giriş sayfasına yönlendir
    header('Location: /login.php?registered=1');
    exit;
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        exit('Bu kullanıcı adı zaten alınmış.');
    }
    exit('Kayıt sırasında hata: ' . $e->getMessage());
}
