<?php
// public_html/entry_view.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/backend/db.php';

// 1) Girdi verisini çek
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM entries WHERE id = :id AND user_id = :u");
$stmt->execute(['id' => $id, 'u' => $_SESSION['user_id']]);
$e = $stmt->fetch() ?: exit('Girdi bulunamadı veya yetkiniz yok.');

// 2) Etiketleri çek
$stmtTags = $pdo->prepare("
  SELECT t.name
    FROM tags t
    JOIN entry_tags et ON et.tag_id = t.id
   WHERE et.entry_id = ?
");
$stmtTags->execute([$e['id']]);
$tags = $stmtTags->fetchAll(PDO::FETCH_COLUMN);

// 3) Resimleri çek
$stmtImgs = $pdo->prepare("
  SELECT filename
    FROM entry_images
   WHERE entry_id = ?
ORDER BY created_at ASC
");
$stmtImgs->execute([$e['id']]);
$images = $stmtImgs->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>📄 <?= htmlspecialchars($e['title']) ?> | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Sayfaya özel: kart benzeri flex düzeni */
    .entry-view {
      display: flex;
      gap: 1rem;
      background: #fff;
      padding: 1rem;
      margin: 2rem auto;
      max-width: 800px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .entry-view .entry-images {
      flex-shrink: 0;
      width: 200px;
    }
    .entry-view .entry-images img {
      width: 100%;
      border-radius: 4px;
      border: 1px solid #ddd;
      object-fit: cover;
    }
    .entry-view .entry-content {
      flex: 1;
      line-height: 1.6;
    }
    .entry-view .meta {
      color: #666;
      font-size: .9rem;
      margin-bottom: .5rem;
    }
    .entry-view .tags {
      margin: .5rem 0;
    }
    .entry-view .tags .tag {
      display: inline-block;
      background: #e0e0e0;
      border-radius: 4px;
      padding: .2rem .5rem;
      margin-right: .5rem;
      font-size: .85rem;
    }
    .entry-actions {
      margin-top: 1rem;
    }
    .entry-actions a {
      margin-right: 1rem;
      color: #4a90e2;
      text-decoration: none;
    }
    .entry-actions a:hover {
      text-decoration: underline;
    }
    @media (max-width: 600px) {
      .entry-view { flex-direction: column; }
      .entry-view .entry-images { width: 100%; }
    }
  </style>
</head>
<body>

  <header class="container">
    <h1>📄 <?= htmlspecialchars($e['title']) ?></h1>
    <nav class="nav-links">
      <a href="entries.php">← Günlüklere Dön</a> |
      <a href="entry_edit.php?id=<?= $e['id'] ?>">Düzenle</a> |
      <a href="backend/entry/delete_process.php?id=<?= $e['id'] ?>"
         onclick="return confirm('Bu girdi silinsin mi?')">Sil</a> |
      <a href="entries.php">Anasayfa</a>
    </nav>
  </header>

  <main class="container entry-view">
  <!-- 1) Resim -->
  <?php if ($images): ?>
    <div class="entry-images">
      <?php foreach ($images as $img): ?>
        <img src="uploads/<?= htmlspecialchars($img) ?>" alt="Girdi resmi">
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- 2) Tüm metin içeriği -->
  <div class="entry-info">
    <p class="meta">
      Girdi Tarihi: <?= htmlspecialchars($e['entry_date']) ?><br>
      Oluşturulma: <?= htmlspecialchars($e['created_at']) ?>
    </p>

    <?php if ($tags): ?>
      <div class="tags">
        <?php foreach ($tags as $tag): ?>
          <span class="tag">#<?= htmlspecialchars($tag) ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="entry-content">
      <?= $e['content'] /* raw HTML */ ?>
    </div>

    <div class="entry-actions">
      <!-- isterseniz favori, düzenle, sil linkleri buraya -->
    </div>
  </div>
</main>


</body>
</html>
