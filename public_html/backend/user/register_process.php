<?php
// public_html/backend/user/register_process.php
session_start();
require __DIR__ . '/../db.php';

$username         = trim($_POST['username']         ?? '');
$password         =          $_POST['password']     ?? '';
$password_confirm =          $_POST['password_confirm'] ?? '';
$error            = '';

// 1) Zorunlu alanları kontrol et
if ($username === '' || $password === '' || $password_confirm === '') {
    $error = 'Lütfen tüm alanları doldurun.';
}
// 2) Şifre teyidi
elseif ($password !== $password_confirm) {
    $error = 'Şifreler uyuşmuyor.';
}

// 3) Halen hata yoksa kullanıcı adı özgün mü bak
if (!$error) {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $error = 'Bu kullanıcı adı zaten alınmış.';
    }
}

// 4) Hata varsa ekrana şık bir kutuyla göster
if ($error):
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kayıt Hatası | Online Günlük</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    .error-box {
      max-width: 400px;
      margin: 5rem auto;
      padding: 1.5rem;
      background: #fff;
      border: 1px solid #e74c3c;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,.1);
      text-align: center;
    }
    .error-box p {
      color: #e74c3c;
      font-weight: bold;
      margin-bottom: 1rem;
    }
    .error-box a.btn {
      display: inline-block;
      padding: .5rem 1rem;
      background: #4a90e2;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
    }
    .error-box a.btn:hover {
      background: #3b7dc1;
    }
  </style>
</head>
<body>
  <div class="error-box">
    <p><?= htmlspecialchars($error) ?></p>
    <a href="/register.php" class="btn">Geri Dön</a>
  </div>
</body>
</html>
<?php
    exit;
endif;

// 5) Kayıt başarılı — veritabanına ekle
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
$stmt->execute(['u' => $username, 'p' => $hash]);

// Oturum aç ve ana sayfaya gönder
$_SESSION['user_id'] = $pdo->lastInsertId();
header('Location: /entries.php');
exit;