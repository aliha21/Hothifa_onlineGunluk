<?php
// public_html/entry_edit.php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
require __DIR__ . '/backend/db.php';

// 1) Girdi verisini al
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM entries WHERE id = :id AND user_id = :u");
$stmt->execute(['id' => $id, 'u' => $_SESSION['user_id']]);
$e = $stmt->fetch() ?: exit('Girdi bulunamadı veya yetkiniz yok.');

// 2) Etiketleri al
$stmtTags = $pdo->prepare("
  SELECT t.name
  FROM tags t
  JOIN entry_tags et ON et.tag_id = t.id
  WHERE et.entry_id = ?
");
$stmtTags->execute([$e['id']]);
$tags = $stmtTags->fetchAll(PDO::FETCH_COLUMN);

// 3) Resimleri al
$stmtImgs = $pdo->prepare("
  SELECT id, filename
  FROM entry_images
  WHERE entry_id = ?
  ORDER BY created_at ASC
");
$stmtImgs->execute([$e['id']]);
$images = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Girdi Düzenle | <?= htmlspecialchars($e['title']) ?></title>
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
    <h1>Girdi Düzenle</h1>
    <nav class="profile-nav">
      <a href="entries.php">← Günlüklere Dön</a> |
      <a href="favorites.php">⭐ Favoriler</a> |
      <a href="profile.php">👤 Profilim</a> |
      <a href="logout.php">Çıkış Yap</a>
    </nav>
  </header>

  <!-- Düzenleme Formu -->
  <main>
    <form
      action="backend/entry/edit_process.php"
      method="post"
      enctype="multipart/form-data"
      onsubmit="tinymce.triggerSave()"
      class="entry-form"
    >
      <input type="hidden" name="id" value="<?= $e['id'] ?>">

      <div class="field">
        <label for="title">Başlık *</label>
        <input
          type="text"
          id="title"
          name="title"
          value="<?= htmlspecialchars($e['title']) ?>"
          required
        >      </div>

<div class="field">
<label> Girdi Tarihi:<br>
  <input type="date"
         name="entry_date"
         value="<?= htmlspecialchars($e['entry_date']) ?>"
         required>
</label><br><br> </div>

      <div class="field">
        <label for="content">İçerik *</label>
        <textarea id="content" name="content"><?= htmlspecialchars($e['content']) ?></textarea>
      </div>

      <div class="field">
        <label for="tags">Etiketler (virgülle ayrılmış)</label>
        <input
          type="text"
          id="tags"
          name="tags"
          value="<?= htmlspecialchars(implode(', ', $tags)) ?>"
          placeholder="örnek: iş, özel, gezi"
        >
      </div>

      <?php if ($images): ?>
      <div class="field">
        <label>Mevcut Fotoğraflar:</label>
        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
          <?php foreach ($images as $img): ?>
            <div style="text-align:center;">
              <img
                src="uploads/<?= htmlspecialchars($img['filename']) ?>"
                style="width:120px; border:1px solid #ddd; border-radius:4px;"
                alt="">
              <div>
                <label>
                  <input
                    type="checkbox"
                    name="delete_images[]"
                    value="<?= $img['id'] ?>">
                  Sil
                </label>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="field">
        <label for="new_images">Yeni Fotoğraflar (birden fazla)</label>
        <input
          type="file"
          id="new_images"
          name="new_images[]"
          multiple
          accept="image/*"
        >
      </div>

      <div class="buttons">
        <button type="submit">Güncelle</button>
        <a href="entries.php">İptal</a>
      </div>
    </form>
  </main>

</body>
</html>
