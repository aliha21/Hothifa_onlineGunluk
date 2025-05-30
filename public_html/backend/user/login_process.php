<?php
// public_html/backend/user/login_process.php

session_start();
require __DIR__ . '/../db.php';

// POST ile gelen verileri al
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$error = '';
if ($username === '' || $password === '') {
    $error = 'Kullanıcı adı ve şifre girmeniz gerekiyor.';
} else {
    // Kullanıcı kaydını çek
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :u");
    $stmt->execute(['u' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $error = 'Kullanıcı adı veya şifre yanlış.';
    } else {
        // Giriş başarılı
        $_SESSION['user_id'] = $user['id'];
        header('Location: /entries.php');
        exit;
    }
}

// Eğer buraya geldiysek hata var:
?><!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Hatası | Online Günlük</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    body { background: #f5f5f5; }
    .error-container {
      max-width: 400px;
      margin: 4rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    .error-container h1 {
      margin-top: 0;
      color: #e74c3c;
      font-size: 1.8rem;
    }
    .error-container p {
      color: #333;
      margin: 1rem 0 2rem;
      font-size: 1rem;
    }
    .error-container a.btn {
      display: inline-block;
      padding: 0.6rem 1.2rem;
      background: #4a90e2;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 500;
    }
    .error-container a.btn:hover {
      background: #3b7dc1;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <h1>Giriş Başarısız</h1>
    <p><?= htmlspecialchars($error) ?></p>
    <a href="/login.php" class="btn">Tekrar Deneyin</a>
  </div>
</body>
</html>
