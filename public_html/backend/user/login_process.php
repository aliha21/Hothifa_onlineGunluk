<?php
// public_html/backend/user/login_process.php

// 1. Hata raporlamayı açalım (geliştirme aşamasında yardımcı olur)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Oturumu başlat
session_start();

// 3. PDO bağlantısını yükle
require __DIR__ . '/../db.php';

// 4. Form verilerini al
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// 5. Eksik alan kontrolü
if ($username === '' || $password === '') {
    exit('Lütfen kullanıcı adı ve şifre girin.');
}

// 6. Kullanıcıyı veritabanından çek
$sql  = "SELECT id, username, password FROM users WHERE username = :u";
$stmt = $pdo->prepare($sql);
$stmt->execute(['u' => $username]);
$user = $stmt->fetch();

// 7. Şifre kontrolü
if ($user && password_verify($password, $user['password'])) {
    // Başarılı giriş → session’a yaz
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];

    // 8. Girişten sonra entries.php sayfasına yönlendir
    header('Location: /entries.php');
    exit;
} else {
    // Başarısızsa hata ver
    exit('Kullanıcı adı veya şifre yanlış.');
}
