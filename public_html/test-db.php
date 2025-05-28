<?php
// public_html/test-db.php ya da /htdocs/test-db.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısını çağır
require __DIR__ . '/backend/db.php';

// Dosyanın yüklendiğini doğrulamak için
echo "<p>✅ test-db.php çalışıyor</p>";

// $pdo var mı diye kontrol et
if (isset($pdo)) {
    echo '<p>✅ Veritabanı bağlantısı başarılı!</p>';
} else {
    echo '<p>❌ Veritabanı bağlantısı kurulamadı.</p>';
}
