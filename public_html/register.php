<!-- public_html/register.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kayıt Ol | Online Günlük</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Yeni Kullanıcı Kaydı</h1>
  <form action="backend/user/register_process.php" method="post">
    <label>
      Kullanıcı Adı:<br>
      <input type="text" name="username" required>
    </label><br><br>
    <label>
      Şifre:<br>
      <input type="password" name="password" required>
    </label><br><br>
    <button type="submit">Kayıt Ol</button>
  </form>
  <p>Zaten üye misiniz? <a href="login.php">Giriş Yapın</a></p>
</body>
</html>
