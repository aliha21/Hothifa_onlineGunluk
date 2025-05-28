<!-- public_html/login.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Kullanıcı Girişi</h1>
  <?php if (isset($_GET['registered'])): ?>
    <p style="color:green;">Kayıt başarılı. Giriş yapabilirsiniz.</p>
  <?php endif; ?>
  <form action="backend/user/login_process.php" method="post">
    <label>
      Kullanıcı Adı:<br>
      <input type="text" name="username" required>
    </label><br><br>
    <label>
      Şifre:<br>
      <input type="password" name="password" required>
    </label><br><br>
    <button type="submit">Giriş Yap</button>
  </form>
  <p>Hesabınız yok mu? <a href="register.php">Kayıt Olun</a></p>
</body>
</html>
