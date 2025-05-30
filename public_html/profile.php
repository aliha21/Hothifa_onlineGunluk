<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
require __DIR__ . '/backend/db.php';

// Kullanıcıyı çek
$stmt = $pdo->prepare("SELECT username, full_name, email, avatar FROM users WHERE id = ?");
$stmt->execute([ $_SESSION['user_id'] ]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: exit('Kullanıcı bulunamadı.');

// Avatar dosyası
$avatarFile = $user['avatar']
  ? 'uploads/avatars/' . $user['avatar']
  : 'uploads/default-avatar.png';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Profilim | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Başlık + Nav -->
  <header class="profile-header">
    <h1>👤 Profilim</h1>
    <nav class="profile-nav">
      <a href="entries.php">← Günlüklere Dön</a> |
      <a href="profile_edit.php">Bilgileri Güncelle</a> |
      <a href="logout.php">Çıkış Yap</a>
    </nav>
  </header>

  <!-- Profil Kartı -->
  <div class="profile-card">
    <img src="<?= htmlspecialchars($avatarFile) ?>"
         alt="Profil Fotoğrafı"
         class="profile-photo">

    <h2>
      <?= htmlspecialchars($user['full_name'] ?: $user['username']) ?>
    </h2>

    <div class="field">
      <label>Kullanıcı Adı:</label>
      <span><?= htmlspecialchars($user['username']) ?></span>
    </div>

    <?php if ($user['email']): ?>
      <div class="field">
        <label>E-posta:</label>
        <span><?= htmlspecialchars($user['email']) ?></span>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>
