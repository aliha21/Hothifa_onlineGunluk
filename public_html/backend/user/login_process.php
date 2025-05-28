<?php
// public_html/backend/user/login_process.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/../db.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $error = 'Lütfen tüm alanları doldurun.';
} else {
    $sql  = "SELECT id, username, password FROM users WHERE username = :u";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['u' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Giriş başarılı
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: /entries.php');
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre yanlış.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Hatası | Online Günlük</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <div class="error-box">
    <p><?= htmlspecialchars($error) ?></p>
    <a href="/login.php" class="btn">Tekrar Deneyin</a>
  </div>
</body>
</html>
