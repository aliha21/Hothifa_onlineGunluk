<?php
// public_html/backend/entry/edit_process.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require __DIR__ . '/../db.php';

$userId     = $_SESSION['user_id'];
$entryId    = intval($_POST['id'] ?? 0);
$title      = trim($_POST['title'] ?? '');
$content    = trim($_POST['content'] ?? '');
$entryDate  = $_POST['entry_date'] ?? date('Y-m-d');
$tagString  = trim($_POST['tags'] ?? '');
$deleteImgs = $_POST['delete_images'] ?? [];
$newImages  = $_FILES['new_images'] ?? null;

$error = '';

// Basic validation
if ($title === '') {
    $error = 'Başlık boş olamaz.';
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $entryDate)) {
    $error = 'Geçersiz tarih formatı.';
}

if ($error) {
    $_SESSION['flash_error'] = $error;
    header("Location: /entry_edit.php?id={$entryId}");
    exit;
}

try {
    $pdo->beginTransaction();

    // 1) Update entry row
    $sql = "
        UPDATE entries
           SET title      = :t,
               content    = :c,
               entry_date = :d
         WHERE id = :id
           AND user_id = :u
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        't'   => $title,
        'c'   => $content,
        'd'   => $entryDate,
        'id'  => $entryId,
        'u'   => $userId,
    ]);

    // 2) Update tags
    // Remove old tags
    $stmt = $pdo->prepare("DELETE FROM entry_tags WHERE entry_id = ?");
    $stmt->execute([$entryId]);

    // Insert new tags
    if ($tagString !== '') {
        $tags = array_unique(array_filter(array_map('trim', explode(',', $tagString))));
        foreach ($tags as $tagName) {
            // find or create tag
            $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ?");
            $stmt->execute([$tagName]);
            $tagId = $stmt->fetchColumn();
            if (!$tagId) {
                $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
                $stmt->execute([$tagName]);
                $tagId = $pdo->lastInsertId();
            }
            // link entry to tag
            $stmt = $pdo->prepare("INSERT INTO entry_tags (entry_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$entryId, $tagId]);
        }
    }

    // 3) Delete selected images
    if (!empty($deleteImgs) && is_array($deleteImgs)) {
        $stmt = $pdo->prepare("SELECT filename FROM entry_images WHERE id = ? AND entry_id = ?");
        $delStmt = $pdo->prepare("DELETE FROM entry_images WHERE id = ?");
        foreach ($deleteImgs as $imgId) {
            $stmt->execute([$imgId, $entryId]);
            $filename = $stmt->fetchColumn();
            if ($filename) {
                @unlink(__DIR__ . '/../../uploads/' . $filename);
                $delStmt->execute([$imgId]);
            }
        }
    }

    // 4) Handle new uploads
    if ($newImages && isset($newImages['tmp_name']) && is_array($newImages['tmp_name'])) {
        $uploadStmt = $pdo->prepare("
            INSERT INTO entry_images (entry_id, filename, created_at)
            VALUES (:eid, :fn, NOW())
        ");
        for ($i = 0; $i < count($newImages['tmp_name']); $i++) {
            if (is_uploaded_file($newImages['tmp_name'][$i])) {
                $ext = pathinfo($newImages['name'][$i], PATHINFO_EXTENSION);
                $fn  = uniqid('img_') . '.' . $ext;
                $dst = __DIR__ . '/../../uploads/' . $fn;
                if (move_uploaded_file($newImages['tmp_name'][$i], $dst)) {
                    $uploadStmt->execute([
                        'eid' => $entryId,
                        'fn'  => $fn,
                    ]);
                }
            }
        }
    }

    $pdo->commit();

    header("Location: /entries.php");
    exit;

} catch (Exception $ex) {
    $pdo->rollBack();
    error_log($ex->getMessage());
    $_SESSION['flash_error'] = 'Güncelleme sırasında bir hata oluştu.';
    header("Location: /entry_edit.php?id={$entryId}");
    exit;
}
