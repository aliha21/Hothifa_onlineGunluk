<?php
// public_html/favorites.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/backend/db.php';

// Favori girdileri çek
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
    ) AS thumb
  FROM entries e
  JOIN favorites f ON f.entry_id = e.id
  WHERE f.user_id = :u
  ORDER BY e.entry_date DESC, e.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['u' => $_SESSION['user_id']]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Favorilerim | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Header / Nav tamamen entries.php ile aynı */
    .container { max-width:900px; margin:2rem auto; padding:0 1rem; }
    header h1 { background:#4a90e2; color:#fff; padding:1rem; border-radius:6px; text-align:center; margin-bottom:1rem; font-size:2rem; }
    nav.nav-links { display:flex; justify-content:center; gap:1rem; margin-bottom:1.5rem; }
    nav.nav-links a { color:#4a90e2; text-decoration:none; font-weight:500; }
    nav.nav-links a:hover { text-decoration:underline; }

    /* Favoriler listesi kartları */
    .entries-list { list-style:none; padding:0; margin:0; }
    .entries-list li {
      background:#fff;
      margin-bottom:1.25rem;
      border-radius:8px;
      overflow:hidden;
      box-shadow:0 2px 8px rgba(0,0,0,0.05);
      display:flex;
      align-items:flex-start;
      width:100%;
    }
    .entries-list img.thumb {
      width:200px;
      height:200px;
      object-fit:cover;
      flex-shrink:0;
    }
    .entry-info {
      padding:1rem;
      flex:1;
    }
    .entry-info .entry-date,
    .entry-info .created-date {
      color:#666;
      font-size:.85rem;
      margin-bottom:.5rem;
    }
    .entry-info .entry-title {
      font-size:1.3rem;
      font-weight:600;
      margin:.25rem 0 .75rem;
    }
    .entry-actions a {
      margin-right:1rem;
      color:#4a90e2;
      text-decoration:none;
    }
    .entry-actions a:hover { text-decoration:underline; }
    @media (max-width:600px) {
      .entries-list li { flex-direction:column; }
      .entries-list img.thumb { width:100%; height:auto; }
    }
  </style>
</head>
<body>

    <header class="container">
    <h1>⭐ Favorilerim</h1>
    <nav class="nav-links">
      <a href="entries.php">← Günlüklere Dön</a> |
      <a href="logout.php">Çıkış</a>
    </nav>
  </header>

  <main class="container">
    <?php if (empty($favorites)): ?>
      <p style="text-align:center; color:#555;">
        Henüz favori girdi eklemediniz.
      </p>
    <?php else: ?>
      <ul class="entries-list">
        <?php foreach ($favorites as $e): ?>
        <li>
          <?php if ($e['thumb']): ?>
            <img src="uploads/<?= htmlspecialchars($e['thumb']) ?>"
                 alt="Girdi resmi" class="thumb">
          <?php endif; ?>

          <div class="entry-info">
            <div class="entry-date">
              Girdi: <?= htmlspecialchars($e['entry_date']) ?>
            </div>
            <div class="entry-title">
              <?= htmlspecialchars($e['title']) ?>
            </div>
            <div class="created-date">
              Kaydedilme: <?= htmlspecialchars($e['created_at']) ?>
            </div>

            <div class="entry-actions">
              <a href="backend/entry/toggle_fav.php?id=<?= $e['id'] ?>&action=remove">
                ⭐ Kaldır
              </a>
              |
              <a href="entry_view.php?id=<?= $e['id'] ?>">Görüntüle</a>
            </div>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </main>

</body>
</html>
