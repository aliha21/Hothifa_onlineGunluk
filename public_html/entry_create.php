<?php
// public_html/entry_create.php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>+ Yeni Girdi Oluştur | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.3/tinymce.min.js"></script>
  <script>
    tinymce.init({
      selector: 'textarea[name=content]',
      menubar: false,
      plugins: 'lists link',
      toolbar: 'bold italic underline | bullist numlist | link',
      branding: false,
      height: 300
    });
  </script>
</head>
<body>

  <!-- Başlık + Nav -->
  <header class="profile-header">
    <h1>+ Yeni Girdi Oluştur</h1>
    <nav class="profile-nav">
      <a href="entries.php">← Günlüklere Dön</a> |
      <a href="favorites.php">⭐ Favoriler</a> |
      <a href="profile.php">👤 Profilim</a> |
      <a href="logout.php">Çıkış Yap</a>
    </nav>
  </header>

  <!-- Girdi Oluşturma Formu -->
  <main>
    <form
      action="backend/entry/create_process.php"
      method="post"
      enctype="multipart/form-data"
      onsubmit="tinymce.triggerSave()"
      class="entry-form"
    >
      <div class="field">
        <label for="title">Başlık *</label>
        <input type="text" id="title" name="title" required>
      </div>
      
     <div class="field"> 
 <label>
  Girdi Tarihi:<br>
  <input type="date" name="entry_date"
         value="<?= date('Y-m-d') ?>" required>
</label><br><br>
 </div>


      <div class="field">
        <label for="content">İçerik *</label>
        <textarea id="content" name="content"></textarea>
      </div>

      <div class="field">
        <label for="tags">Etiketler (virgülle ayrılmış)</label>
        <input
          type="text"
          id="tags"
          name="tags"
          placeholder="örnek: iş, özel, gezi"
        >
      </div>

      <div class="field">
        <label for="new_images">Fotoğraflar (birden fazla seçebilirsiniz)</label>
        <input
          type="file"
          id="new_images"
          name="new_images[]"
          multiple
          accept="image/*"
        >
      </div>

      <div class="buttons">
        <button type="submit">Kaydet</button>
        <a href="entries.php">İptal</a>
      </div>
    </form>
  </main>

</body>
</html>
