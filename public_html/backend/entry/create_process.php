<?php
// public_html/backend/entry/create_process.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require __DIR__ . '/../db.php';

$userId  = $_SESSION['user_id'];
$title   = trim($_POST['title']   ?? '');
$content = trim($_POST['content'] ?? '');
$tagsRaw = trim($_POST['tags']    ?? ''); // virgülle ayrılmış etiketler

// Basit doğrulama
$errors = [];
if ($title === '') {
    $errors[] = 'Başlık boş olamaz.';
}

if (!empty($errors)) {
    // Hata çıkışı (dilerseniz burayı daha şık HTML/CSS ile süsleyebilirsiniz)
    echo '<h1>Hata</h1><ul>';
    foreach ($errors as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    echo '</ul><p><a href="/entry_create.php">Geri dön</a></p>';
    exit;
}

try {
    // Başlangıç: Transaction
    $pdo->beginTransaction();

    // 1) entries tablosuna ekle
    
    // formdan:
$entryDate = $_POST['entry_date'] ?? date('Y-m-d');

// 1) entries tablosuna ekle

    $sql = "
        INSERT INTO entries
            (user_id, title, content, entry_date, created_at)
        VALUES
            (:u, :t, :c, :d, NOW())
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'u' => $userId,
        't' => $title,
        'c' => $content,
        // Eğer form üzerinde ayrı bir tarih seçimi yoksa CURRENT_DATE kullanabilirsiniz:
         'd' => $entryDate,
    ]);
    $entryId = $pdo->lastInsertId();

    // 2) Etiketleri ekle
    if ($tagsRaw !== '') {
        // virgülle ayır, hem küçük harfe çevir hem boşları temizle
        $tagNames = array_filter(array_map('trim', explode(',', $tagsRaw)));
        foreach ($tagNames as $name) {
            // 2.a) tags tablosuna ekle (varsa pas geç)
            $sqlTag = "INSERT IGNORE INTO tags (name) VALUES (:name)";
            $stmtTag = $pdo->prepare($sqlTag);
            $stmtTag->execute(['name' => $name]);

            // 2.b) entry_tags ilişkilendirme
            $sqlET = "INSERT INTO entry_tags (entry_id, tag_id)
                      VALUES (:e, (SELECT id FROM tags WHERE name = :name))";
            $stmtET = $pdo->prepare($sqlET);
            $stmtET->execute([
                'e'    => $entryId,
                'name' => $name
            ]);
        }
    }

    // 3) Fotoğrafları kaydet
    if (!empty($_FILES['new_images'])
        && isset($_FILES['new_images']['tmp_name'])
    ) {
        foreach ($_FILES['new_images']['tmp_name'] as $i => $tmp) {
            if (!is_uploaded_file($tmp)) {
                continue;
            }
            $origName = $_FILES['new_images']['name'][$i];
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            // filename örn: entry_123_1654378290.jpg
            $filename = 'entry_' . $entryId . '_' . time() . "_{$i}." . $ext;
            $dest = __DIR__ . '/../../uploads/' . $filename;

            if (move_uploaded_file($tmp, $dest)) {
                $sqlImg = "
                    INSERT INTO entry_images
                      (entry_id, filename, created_at)
                    VALUES
                      (:e, :f, NOW())
                ";
                $stmtImg = $pdo->prepare($sqlImg);
                $stmtImg->execute([
                    'e' => $entryId,
                    'f' => $filename
                ]);
            }
        }
    }

    // 4) Her şey yolundaysa onayla
    $pdo->commit();

    // Ana sayfaya geri dön
    header('Location: /entries.php');
    exit;

} catch (Exception $ex) {
    // hata durumunda geri al
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // hata mesajını logla
    error_log($ex->getMessage());
    // kullanıcıya nazik bir hata göster
    echo '<h1>Bir hata oluştu</h1>';
    echo '<p>Lütfen tekrar deneyin.</p>';
    echo '<p><a href="/entry_create.php">Geri dön</a></p>';
    exit;
}
