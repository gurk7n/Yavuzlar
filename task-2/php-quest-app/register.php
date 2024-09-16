<?php
session_start();
include 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullaniciAdi = $_POST['kullaniciAdi'];
    $parola = $_POST['parola'];
    $rol = $_POST['rol'];

    if (empty($kullaniciAdi) || empty($parola)) {
        $error = "Kullanıcı adı veya parola boş olamaz!";
    } else {
        $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE kullaniciAdi = :kullaniciAdi");
        $stmt->bindParam(':kullaniciAdi', $kullaniciAdi);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = "Bu kullanıcı adı zaten kullanılıyor!";
        } else {
            $stmt = $db->prepare("INSERT INTO kullanicilar (kullaniciAdi, parola, rol, dogru, yanlis, skor) VALUES (:kullaniciAdi, :parola, :rol, 0, 0, 0)");
            $stmt->bindParam(':kullaniciAdi', $kullaniciAdi);
            $stmt->bindParam(':parola', $parola);
            $stmt->bindParam(':rol', $rol);
            $stmt->execute();

            header("Location: login.php");
            exit;
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Kayıt Ol - Quest App</title>
</head>
<body>
    <img src="images/logo.png" class="adminLogo" />
    <h1 class="adminTxt">Kayıt Ol</h1>
    <div class="adminBox">
        <form action="register.php" method="post">
            <div class="row">
                <label for="kullaniciAdi">Kullanıcı Adı</label>
                <input type="text" name="kullaniciAdi" id="kullaniciAdi" required />
            </div>
            <div class="row">
                <label for="parola">Parola</label>
                <input type="password" name="parola" id="parola" required />
            </div>
            <div class="row">
                <label for="rol">Rol Seçimi</label>
                <select name="rol" id="rol" required>
                    <option value="admin">Admin</option>
                    <option value="ogrenci">Öğrenci</option>
                </select>
            </div>
            <div class="sag">
                <button type="submit">Hesap Oluştur</button>
            </div>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
