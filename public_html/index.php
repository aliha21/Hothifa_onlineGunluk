<?php
session_start();

// 1. Eğer oturum yoksa login sayfasına gönder
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// 2. Oturum varsa doğrudan entries.php’ye yönlendir
header('Location: /entries.php');
exit;
