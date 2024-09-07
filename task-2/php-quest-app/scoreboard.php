<?php
include 'db.php';

    $stmt = $db->query("SELECT kullaniciAdi, skor, dogru, yanlis FROM kullanicilar ORDER BY skor DESC");
    $kullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Quest App - Skor Tablosu</title>
    <link rel="stylesheet" href="css/scoreboard.css">
</head>
<body>
    <img src="images/logo.png" class="adminLogo" />
    <h1 class="adminTxt">Skor Tablosu</h1>
        <table>
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Kullanıcı</th>
                    <th>Doğru</th>
                    <th>Yanlış</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $siraNo = 1;
                foreach ($kullanicilar as $kullanici): ?>
                <tr>
                    <td><?php echo $siraNo++; ?></td>
                    <td><?php echo htmlspecialchars($kullanici['kullaniciAdi']); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['dogru']); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['yanlis']); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['skor']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
      <a href="index.php" class="exitLink"><p class="exitTxt">Geri Dön</p></a>
</body>
</html>
