<?php
// public_html/backend/user/update_profile_process.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require __DIR__ . '/../db.php';

$uid   = $_SESSION['user_id'];
$name  = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pw    = $_POST['new_password'] ?? '';
$pw2   = $_POST['new_password_confirm'] ?? '';

// Basit doğrulamalar
if ($name === '') {
    $error = 'Tam isim boş olamaz.';
} elseif ($pw !== '' && $pw !== $pw2) {
    $error = 'Yeni şifreler uyuşmuyor.';
}

if (!empty($error)):
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Hata | Bilgileri Güncelle</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Bilgileri Güncelle</h1>
    <div class="error-box">
      <p><?= htmlspecialchars($error) ?></p>
      <a href="/profile_edit.php" class="btn">Geri Dön</a>
    </div>
  </div>
</body>
</html>
<?php
  exit;
endif;

try {
    // 1) Adı ve e-postayı güncelle
    $sql = "UPDATE users SET full_name = :n, email = :e WHERE id = :u";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'n' => $name,
      'e' => $email ?: null,
      'u' => $uid
    ]);

    // 2) Şifre değişmişse hashleyip güncelle
    if ($pw !== '') {
      $hash = password_hash($pw, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET password = :p WHERE id = :u");
      $stmt->execute(['p' => $hash, 'u' => $uid]);
    }

    // 3) Profil fotoğrafı (opsiyonel)
    if (!empty($_FILES['avatar']['tmp_name'])) {
      $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
      $fn  = 'user_' . $uid . '.' . $ext;
      move_uploaded_file(
        $_FILES['avatar']['tmp_name'],
        __DIR__ . '/../../uploads/avatars/' . $fn
      );
      $stmt = $pdo->prepare("UPDATE users SET avatar = :a WHERE id = :u");
      $stmt->execute(['a' => $fn, 'u' => $uid]);
    }

    // Başarılıysa profil sayfasına dön
    header('Location: /profile.php');
    exit;

} catch (PDOException $ex) {
    error_log($ex->getMessage());
    $error = 'Güncelleme sırasında bir sorun oluştu.';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Hata | Bilgileri Güncelle</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Bilgileri Güncelle</h1>
    <div class="error-box">
      <p><?= htmlspecialchars($error) ?></p>
      <a href="/profile_edit.php" class="btn">Geri Dön</a>
    </div>
  </div>
</body>
</html>
<?php
    exit;
}
