<?php
session_start();

if (!isset($_SESSION['rol'])) {
    echo "Bu sayfaya erişim yetkiniz yok!";
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT dogru, yanlis FROM kullanicilar WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_stats = $stmt->fetch(PDO::FETCH_ASSOC);

$correct_count = $user_stats['dogru'];
$incorrect_count = $user_stats['yanlis'];

$query = "SELECT * FROM sorular WHERE id NOT IN (SELECT soru_id FROM cozumler WHERE kullanici_id = :user_id)";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$sorular = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['soru_id']) && isset($_POST['cevap'])) {
        $soru_id = $_POST['soru_id'];
        $cevap = $_POST['cevap'];

        $stmt = $db->prepare("SELECT cevap FROM sorular WHERE id = :soru_id");
        $stmt->bindParam(':soru_id', $soru_id, PDO::PARAM_INT);
        $stmt->execute();
        $correct_answer = $stmt->fetchColumn();

        $dogruMu = ($cevap === $correct_answer) ? 1 : 0;

        $stmt = $db->prepare("INSERT INTO cozumler (kullanici_id, soru_id, dogruMu) VALUES (:user_id, :soru_id, :dogruMu)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':soru_id', $soru_id, PDO::PARAM_INT);
        $stmt->bindParam(':dogruMu', $dogruMu, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($dogruMu) {
                $stmt = $db->prepare("UPDATE kullanicilar SET skor = skor + 10, dogru = dogru + 1 WHERE id = :user_id");
            } else {
                $stmt = $db->prepare("UPDATE kullanicilar SET yanlis = yanlis + 1 WHERE id = :user_id");
            }
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $db->prepare("SELECT dogru, yanlis FROM kullanicilar WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_stats = $stmt->fetch(PDO::FETCH_ASSOC);

            $correct_count = $user_stats['dogru'];
            $incorrect_count = $user_stats['yanlis'];

            header("Location: solve-question.php");
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
    <link rel="stylesheet" href="css/solve-question.css" />
    <title>Quest App - Soru Çöz</title>
</head>
<body>
    <h1 class="topTitle">Soru Çöz</h1>
    <div class="status">
        <p><strong>Doğru Cevaplar:</strong> <?php echo htmlspecialchars($correct_count); ?></p>
        <p><strong>Yanlış Cevaplar:</strong> <?php echo htmlspecialchars($incorrect_count); ?></p>
    </div>
    <?php if (empty($sorular)): ?>
        <div class="solvedBox">
            <p>Çözülmemiş soru yok.</p>
        </div>
    <?php else: ?>
        <?php foreach ($sorular as $soru): ?>
            <div class="questBox">
                <p><strong>Zorluk:</strong> <?php echo htmlspecialchars($soru['zorluk']); ?></p>
                <p><?php echo htmlspecialchars($soru['soru']); ?></p>
                <form method="POST" action="">
                    <input type="hidden" name="soru_id" value="<?php echo htmlspecialchars($soru['id']); ?>" />
                    <div class="options">
                        <div>
                            <input type="radio" id="A_<?php echo htmlspecialchars($soru['id']); ?>" name="cevap" value="A" />
                            <label for="A_<?php echo htmlspecialchars($soru['id']); ?>"><?php echo htmlspecialchars($soru['secenek_A']); ?></label>
                        </div>
                        <div>
                            <input type="radio" id="B_<?php echo htmlspecialchars($soru['id']); ?>" name="cevap" value="B" />
                            <label for="B_<?php echo htmlspecialchars($soru['id']); ?>"><?php echo htmlspecialchars($soru['secenek_B']); ?></label>
                        </div>
                        <div>
                            <input type="radio" id="C_<?php echo htmlspecialchars($soru['id']); ?>" name="cevap" value="C" />
                            <label for="C_<?php echo htmlspecialchars($soru['id']); ?>"><?php echo htmlspecialchars($soru['secenek_C']); ?></label>
                        </div>
                        <div>
                            <input type="radio" id="D_<?php echo htmlspecialchars($soru['id']); ?>" name="cevap" value="D" />
                            <label for="D_<?php echo htmlspecialchars($soru['id']); ?>"><?php echo htmlspecialchars($soru['secenek_D']); ?></label>
                        </div>
                    </div>
                    <button type="submit" class="submitBtn">Cevapla</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <a href="index.php" class="exitLink"><p class="exitTxt">Geri Dön</p></a>
</body>
</html>
