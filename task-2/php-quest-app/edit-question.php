<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok!";
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['soru_id'])) {
    $soru_id = $_POST['soru_id'];
    $soru_metni = $_POST['soru'];
    $secenekA = $_POST['secenek_A'];
    $secenekB = $_POST['secenek_B'];
    $secenekC = $_POST['secenek_C'];
    $secenekD = $_POST['secenek_D'];
    $zorluk = $_POST['zorluk'];
    $dogru_cevap = $_POST['cevap'];

    $stmt = $db->prepare("UPDATE sorular SET soru = :soru, secenek_A = :secenekA, secenek_B = :secenekB, secenek_C = :secenekC, secenek_D = :secenekD, zorluk = :zorluk, cevap = :dogru_cevap WHERE id = :id");
    $stmt->bindParam(':soru', $soru_metni);
    $stmt->bindParam(':secenekA', $secenekA);
    $stmt->bindParam(':secenekB', $secenekB);
    $stmt->bindParam(':secenekC', $secenekC);
    $stmt->bindParam(':secenekD', $secenekD);
    $stmt->bindParam(':zorluk', $zorluk);
    $stmt->bindParam(':dogru_cevap', $dogru_cevap);
    $stmt->bindParam(':id', $soru_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: questions.php");
        exit;
    } else {
        echo "Soru güncellenirken bir hata oluştu!";
    }
} else {
    if (isset($_POST['edit_id'])) {
        $soru_id = $_POST['edit_id'];
        $stmt = $db->prepare("SELECT * FROM sorular WHERE id = :id");
        $stmt->bindParam(':id', $soru_id, PDO::PARAM_INT);
        $stmt->execute();
        $soru = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$soru) {
            echo "Düzenlenecek soru bulunamadı!";
            exit;
        }
    } else {
        echo "Düzenlenecek soru seçilmedi!";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/edit-question.css" />
    <title>Quest App - Soru Düzenle</title>
</head>
<body>
    <div class="addBox">
        <h1 class="topTitle">Soru Düzenle</h1>
        <form method="POST" action="">
            <input type="hidden" name="soru_id" value="<?php echo htmlspecialchars($soru['id']); ?>" />
            <input id="soru" type="text" name="soru" placeholder="Soru Metni" value="<?php echo htmlspecialchars($soru['soru']); ?>" />
            <br />
            <input id="soruA" type="text" class="secenek" name="secenek_A" placeholder="Seçenek A" value="<?php echo htmlspecialchars($soru['secenek_A']); ?>" />
            <input id="soruB" type="text" class="secenek" name="secenek_B" placeholder="Seçenek B" value="<?php echo htmlspecialchars($soru['secenek_B']); ?>" />
            <input id="soruC" type="text" class="secenek" name="secenek_C" placeholder="Seçenek C" value="<?php echo htmlspecialchars($soru['secenek_C']); ?>" />
            <input id="soruD" type="text" class="secenek" name="secenek_D" placeholder="Seçenek D" value="<?php echo htmlspecialchars($soru['secenek_D']); ?>" />
            <br />
            <div class="container">
                <div class="left">
                    Zorluk
                    <div class="radiobuttons">
                        <select id="zorluk" name="zorluk">
                            <option value="Kolay" <?php echo ($soru['zorluk'] == 'Kolay') ? 'selected' : ''; ?>>Kolay</option>
                            <option value="Orta" <?php echo ($soru['zorluk'] == 'Orta') ? 'selected' : ''; ?>>Orta</option>
                            <option value="Zor" <?php echo ($soru['zorluk'] == 'Zor') ? 'selected' : ''; ?>>Zor</option>
                        </select>
                    </div>
                </div>
                <div class="right">
                    Cevap
                    <div class="cevaplar">
                        <input type="radio" id="A" name="cevap" value="A" <?php echo ($soru['cevap'] == 'A') ? 'checked' : ''; ?> />
                        <label for="A">A</label>
                        <input type="radio" id="B" name="cevap" value="B" <?php echo ($soru['cevap'] == 'B') ? 'checked' : ''; ?> />
                        <label for="B">B</label>
                        <input type="radio" id="C" name="cevap" value="C" <?php echo ($soru['cevap'] == 'C') ? 'checked' : ''; ?> />
                        <label for="C">C</label>
                        <input type="radio" id="D" name="cevap" value="D" <?php echo ($soru['cevap'] == 'D') ? 'checked' : ''; ?> />
                        <label for="D">D</label>
                    </div>
                </div>
            </div>
            <br />
            <div>
                <a href="questions.php">
                    <button class="geriBtn" style="margin-right: 20px">Geri Dön</button>
                </a>
                <button type="submit" class="editBtn">Düzenle</button>
            </div>
        </form>
    </div>
</body>
</html>
