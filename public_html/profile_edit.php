<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
require __DIR__ . '/backend/db.php';

// Mevcut kullanıcı verisini çek
$stmt = $pdo->prepare("SELECT full_name, email, avatar FROM users WHERE id = ?");
$stmt->execute([ $_SESSION['user_id'] ]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: exit('Kullanıcı bulunamadı.');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Bilgileri Güncelle | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Başlık + Nav -->
  <header class="profile-header">
    <h1>Bilgileri Güncelle</h1>
    <nav class="profile-nav">
      <a href="profile.php">← Profilim</a> |
      <a href="entries.php">Günlüklere Dön</a> |
      <a href="logout.php">Çıkış Yap</a>
    </nav>
  </header>

  <!-- Düzenleme Formu Kartı -->
  <main>
    <form
      action="backend/user/update_profile_process.php"
      method="post"
      enctype="multipart/form-data"
      class="profile-edit-form"
    >
      <div class="field">
        <label for="full_name">Tam İsim *</label>
        <input
          type="text"
          id="full_name"
          name="full_name"
          value="<?= htmlspecialchars($user['full_name']) ?>"
          required
        >
      </div>

      <div class="field">
        <label for="email">E-posta (opsiyonel)</label>
        <input
          type="email"
          id="email"
          name="email"
          value="<?= htmlspecialchars($user['email']) ?>"
        >
      </div>

      <div class="field">
        <label for="new_password">Yeni Şifre (opsiyonel)</label>
        <input
          type="password"
          id="new_password"
          name="new_password"
          placeholder="Şifreyi değiştirmek için doldurun"
        >
      </div>

      <div class="field">
        <label for="new_password_confirm">Yeni Şifre (Tekrar)</label>
        <input
          type="password"
          id="new_password_confirm"
          name="new_password_confirm"
          placeholder="Yeni şifreyi tekrar girin"
        >
      </div>

      <div class="field">
        <label for="avatar">Profil Fotoğrafı (opsiyonel)</label>
        <input
          type="file"
          id="avatar"
          name="avatar"
          accept="image/*"
        >
      </div>

      <div class="buttons">
        <button type="submit">Güncelle</button>
        <a href="profile.php">İptal</a>
      </div>
    </form>
  </main>

</body>
</html>
