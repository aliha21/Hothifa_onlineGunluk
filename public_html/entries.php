<?php
// public_html/entries.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/backend/db.php';

// Arama terimini al
$search = trim($_GET['search'] ?? '');

// Temel WHERE ve parametreler
$where  = "WHERE e.user_id = :u";
$params = ['u' => $_SESSION['user_id']];

if ($search !== '') {
    $where .= " AND (
      e.title   LIKE :q1
      OR e.content LIKE :q2
      OR EXISTS(
        SELECT 1
        FROM entry_tags et
        JOIN tags t ON t.id = et.tag_id
        WHERE et.entry_id = e.id
          AND t.name LIKE :q3
      )
    )";
    $like = "%{$search}%";
    $params['q1'] = $like;
    $params['q2'] = $like;
    $params['q3'] = $like;
}

// SQL’in tamamı (thumbnail ve is_fav da aynı sorguda)
$sql = "
  SELECT
    e.id,
    e.entry_date,
    e.title,
    e.created_at,
    (SELECT filename
       FROM entry_images
       WHERE entry_id = e.id
       ORDER BY created_at ASC
       LIMIT 1
    ) AS thumb,
    EXISTS(
      SELECT 1
      FROM favorites f
      WHERE f.user_id = e.user_id
        AND f.entry_id = e.id
    ) AS is_fav
  FROM entries e
  {$where}
  ORDER BY e.entry_date DESC, e.created_at DESC
";

// Çalıştır ve al
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Günlüklerim | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>


   <header class="container">
    <h1>Günlüklerim</h1>
    <nav class="nav-links">
      <a href="entry_create.php">+ Yeni Girdi</a> |
      <a href="favorites.php">⭐ Favoriler</a> |
      <a href="profile.php">👤 Profilim</a> |
      <a href="logout.php">Çıkış</a>
    </nav>
  </header>
  


  <main class="container">
    <form class="search-form" method="get" action="entries.php">
      <input type="text"
             name="search"
             value="<?= htmlspecialchars($search) ?>"
             placeholder="Başlık, içerik veya etiket ara…">
      <button type="submit">Ara</button>
    </form>

    <?php if (empty($entries)): ?>
      <p class="empty">Henüz hiç girdi yapmadınız veya sonuç bulunamadı.</p>
    <?php else: ?>
      <ul class="entries-list">
        <?php foreach ($entries as $e):
          // Thumbnail
          $stmtImg = $pdo->prepare(
            "SELECT filename
             FROM entry_images
             WHERE entry_id = ?
             ORDER BY created_at ASC
             LIMIT 1"
          );
          $stmtImg->execute([$e['id']]);
          $thumb = $stmtImg->fetchColumn();

          // Etiketler
          $stmtTags = $pdo->prepare(
            "SELECT t.name
             FROM tags t
             JOIN entry_tags et ON et.tag_id = t.id
             WHERE et.entry_id = ?"
          );
          $stmtTags->execute([$e['id']]);
          $tags = $stmtTags->fetchAll(PDO::FETCH_COLUMN);
        ?>

        
        <li>
          <?php if ($thumb): ?>
            <img src="uploads/<?= htmlspecialchars($thumb) ?>"
                 alt="Girdi resmi"
                 class="thumb">
          <?php endif; ?>

          <div class="info">
            <div class="meta">Girdi Tarihi: <?= htmlspecialchars($e['entry_date']) ?></div>
            <div class="title"><?= htmlspecialchars($e['title']) ?></div>
            <?php if ($tags): ?>
              <div class="tags">
                <?php foreach ($tags as $tag): ?>
                  <span class="tag">#<?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="meta">Kaydedilme: <?= htmlspecialchars($e['created_at']) ?></div>

            <p class="actions">
              <a href="entry_edit.php?id=<?= $e['id'] ?>">Düzenle</a> |
              <a href="backend/entry/delete_process.php?id=<?= $e['id'] ?>"
                 onclick="return confirm('Silinsin mi?')">Sil</a> |
              <?php if ($e['is_fav']): ?>
  <a href="backend/entry/toggle_fav.php?id=<?= $e['id'] ?>&action=remove">⭐</a>
<?php else: ?>
    <a href="backend/entry/toggle_fav.php?id=<?= $e['id'] ?>&action=add">☆</a>
<?php endif; ?>|
              <a href="entry_view.php?id=<?= $e['id'] ?>">Görüntüle</a>
            </p>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </main>

</body>
</html>
