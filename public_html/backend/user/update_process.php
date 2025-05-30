<?php
// public_html/backend/user/update_process.php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../../login.php');
  exit;
}
require __DIR__ . '/../db.php';

$id       = $_SESSION['user_id'];
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '') {
  $error = 'Kullanıcı adı boş olamaz.';
} else {
  try {
    // 1) Kullanıcı adı çakışmasını kontrol et
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :u AND id <> :id");
    $check->execute(['u'=>$username,'id'=>$id]);
    if ($check->fetchColumn() > 0) {
      throw new Exception('Bu kullanıcı adı zaten alınmış.');
    }

    // 2) UPDATE sorgusu hazırla
    if ($password !== '') {
      // şifre de güncellenecekse
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $sql = "UPDATE users
              SET username = :u, password = :p
              WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        'u'=>$username,
        'p'=>$hash,
        'id'=>$id
      ]);
    } else {
      // yalnızca kullanıcı adı
      $sql = "UPDATE users
              SET username = :u
              WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        'u'=>$username,
        'id'=>$id
      ]);
    }

    // session’daki username’i güncelle
    $_SESSION['username'] = $username;

    header('Location: ../../profile.php');
    exit;

  } catch (Exception $ex) {
    $error = $ex->getMessage();
  }
}

if (!empty($error)): ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Hata | Profili Düzenle</title>
  <link rel="stylesheet" href="../../css/style.css">
  <style>
    .error-box {
      max-width:500px; margin:4rem auto; padding:1.5rem;
      background:#fff; border:1px solid #e74c3c;
      border-radius:8px; text-align:center;
      box-shadow:0 2px 5px rgba(0,0,0,0.1);
    }
    .error-box p { color:#e74c3c; font-weight:bold; margin-bottom:1rem; }
    .error-box a.btn {
      display:inline-block; padding:.5rem 1rem;
      background:#4a90e2; color:#fff; border-radius:4px;
      text-decoration:none; font-weight:500;
    }
    .error-box a.btn:hover { background:#3b7dc1; }
  </style>
</head>
<body>
  <div class="error-box">
    <p><?= htmlspecialchars($error) ?></p>
    <a href="../../profile_edit.php" class="btn">Geri Dön</a>
  </div>
</body>
</html>
<?php endif;
