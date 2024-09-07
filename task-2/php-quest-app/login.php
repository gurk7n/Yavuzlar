<?php
session_start();
include 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullaniciAdi = $_POST['kullaniciAdi'];
    $parola = $_POST['parola'];

    if (empty($kullaniciAdi) || empty($parola)) {
        $error = "Kullanıcı adı veya parola boş olamaz!";
    } else {
        $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE kullaniciAdi = :kullaniciAdi");
        $stmt->bindParam(':kullaniciAdi', $kullaniciAdi);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['parola'] === $parola) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['kullaniciAdi'] = $user['kullaniciAdi'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['skor'] = $user['skor'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Kullanıcı adı veya parola hatalı!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Quest App</title>
</head>
<body>
    <img src="images/logo.png" class="adminLogo" />
    <h1 class="adminTxt">Giriş Yap</h1>
    <div class="adminBox">
        <form action="login.php" method="post">
            <div class="row">
                <label for="kullaniciAdi">Kullanıcı Adı</label>
                <input type="text" name="kullaniciAdi" id="kullaniciAdi" />
            </div>
            <div class="row">
                <label class="pass" for="parola">Parola</label>
                <input type="password" name="parola" id="parola" />
            </div>
            <div class="sag">
                <button type="submit">Giriş Yap</button>
            </div>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
