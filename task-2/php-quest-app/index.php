<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$kullaniciAdi = $_SESSION['kullaniciAdi'];
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/index.css" />
    <title>Quest App</title>
</head>
<body>
    <img src="images/logo.png" class="adminLogo" />
    <h1 class="adminTxt">Quest App</h1>
    
    <?php if ($rol === 'admin'): ?>
        <a href="add-question.php">
          <div class="adminBox hoverOrange">
            <img src="images/add.webp" /> Soru Ekle
          </div>
        </a>
        <a href="questions.php">
          <div class="adminBox hoverBlue">
            <img src="images/list.png" /> Soru Düzenle
          </div>
        </a>
    <?php endif; ?>

    <a href="solve-question.php">
      <div class="adminBox"><img src="images/play.webp" /> Soru Çöz</div>
    </a>

    <a href="scoreboard.php">
      <div class="adminBox hoverRed"><img src="images/score.png" /> Skor Tablosu</div>
    </a>

    <div class="kullanici">
      Kullanıcı: <?php echo htmlspecialchars($kullaniciAdi); ?>
      <a href="logout.php" class="exitLink"><p class="exitTxt">Çıkış Yap</p></a>
    </div>
</body>
</html>
