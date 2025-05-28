<?php
// public_html/backend/db.php

$host    = 'sql300.infinityfree.com';        // InfinityFree MySQL Hostname
$db      = 'if0_39101338_online_gunluk';     // InfinityFree MySQL Database Name
$user    = 'if0_39101338';                   // InfinityFree MySQL Username
$pass    = '88a4hjNUv15EL7'; // Mutlaka "MySQL Databases" altında tanımlı parolayı yaz
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Hata detayını görmek için exception mesajını yazdırıyoruz
    exit('❌ Veritabanı bağlantısı kurulamadı: ' . $e->getMessage());
}
