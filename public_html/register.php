<?php
// public_html/register.php

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: entries.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kayıt Ol | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Body gradient */
    body {
      background: linear-gradient(135deg, #42a5f5 0%, #26c6da 100%);
      min-height: 100vh;
    }
    /* Ortalanmış kart */
    .card {
      background: #fff;
      max-width: 400px;
      margin: 4rem auto;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    /* Kart başlığı */
    .card-header {
      background: #4a90e2;
      color: #fff;
      padding: 1.5rem;
      text-align: center;
      font-size: 1.5rem;
    }
    /* Kart içeriği */
    .card-body {
      padding: 1.5rem;
    }
    .card-body label {
      display: block;
      margin-bottom: 0.75rem;
      font-weight: 600;
    }
    .card-body input {
      width: 100%;
      padding: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    .card-body button {
      width: 100%;
      padding: 0.75rem;
      background: #4a90e2;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
    }
    .card-body button:hover {
      background: #3b7dc1;
    }
    /* Kart altı link */
    .card-footer {
      text-align: center;
      padding: 1rem;
      background: #fafafa;
      font-size: 0.9rem;
    }
    .card-footer a {
      color: #4a90e2;
      text-decoration: none;
      font-weight: 500;
    }
    .card-footer a:hover {
      text-decoration: underline;
    }
    /* Üst navigasyon */
    .top-nav {
      display: flex;
      justify-content: center;
      gap: 2rem;
      padding: 1rem 0;
      color: #fff;
      font-weight: 500;
    }
    .top-nav a {
      color: #fff;
      text-decoration: none;
    }
    .top-nav a:hover {
      text-decoration: underline;
    }
    /* Footer */
    footer {
      text-align: center;
      color: #fff;
      margin-top: 2rem;
      padding-bottom: 1rem;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>
  <!-- Üst navigasyon -->
  <nav class="top-nav">
    <a href="about.php">Online Günlük Hakkında</a>|
    <a href="login.php">Giriş Yap</a>
  </nav>

  <!-- Kayıt kartı -->
  <div class="card">
    <div class="card-header">Yeni Kullanıcı Kaydı</div>
    <div class="card-body">
      <form action="backend/user/register_process.php" method="post">
        <label>Kullanıcı Adı *</label>
        <input type="text" name="username" required>

        <label>Tam İsim *</label>
        <input type="text" name="full_name" required>

        <label>E-posta (opsiyonel)</label>
        <input type="email" name="email">

        <label>Şifre *</label>
        <input type="password" name="password" required>

        <label>Şifre (Tekrar) *</label>
        <input type="password" name="password_confirm" required>

        <button type="submit">Kayıt Ol</button>
      </form>
    </div>
    <div class="card-footer">
      Zaten hesabın var mı? <a href="login.php">Giriş Yap</a>
    </div>
  </div>

  <footer>
    © <?= date('Y') ?> Online Günlük. Tüm hakları saklıdır.
  </footer>
</body>
</html>
