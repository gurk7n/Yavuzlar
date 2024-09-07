<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok!";
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $soru = $_POST['soru'];
    $secenekA = $_POST['secenekA'];
    $secenekB = $_POST['secenekB'];
    $secenekC = $_POST['secenekC'];
    $secenekD = $_POST['secenekD'];
    $cevap = $_POST['cevap'];
    $zorluk = $_POST['zorluk'];

    $stmt = $db->prepare("INSERT INTO sorular (soru, secenek_A, secenek_B, secenek_C, secenek_D, cevap, zorluk) VALUES (:soru, :secenekA, :secenekB, :secenekC, :secenekD, :cevap, :zorluk)");
    
    $stmt->bindParam(':soru', $soru);
    $stmt->bindParam(':secenekA', $secenekA);
    $stmt->bindParam(':secenekB', $secenekB);
    $stmt->bindParam(':secenekC', $secenekC);
    $stmt->bindParam(':secenekD', $secenekD);
    $stmt->bindParam(':cevap', $cevap);
    $stmt->bindParam(':zorluk', $zorluk);

    if ($stmt->execute()) {
        header("Location: questions.php");
        exit;
    } else {
        echo "<script>alert('Bir hata oluştu, soru eklenemedi!'); window.location.href = 'add-question.php';</script>";
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/add-question.css" />
    <title>Quest App - Soru Ekle</title>
  </head>
  <body>
    <div class="addBox">
      <h1 class="topTitle">Soru Ekle</h1>
      <form action="add-question.php" method="post">
        <input id="soru" type="text" name="soru" placeholder="Soru Metni" required />
        <br />
        <input id="soruA" type="text" class="secenek" name="secenekA" placeholder="Seçenek A" required />
        <input id="soruB" type="text" class="secenek" name="secenekB" placeholder="Seçenek B" required />
        <input id="soruC" type="text" class="secenek" name="secenekC" placeholder="Seçenek C" required />
        <input id="soruD" type="text" class="secenek" name="secenekD" placeholder="Seçenek D" required />
        <br />
        <div class="container">
          <div class="left">
            Zorluk
            <div class="radiobuttons">
              <select id="zorluk" name="zorluk" required>
                <option value="Kolay">Kolay</option>
                <option value="Orta">Orta</option>
                <option value="Zor">Zor</option>
              </select>
            </div>
          </div>
          <div class="right">
            Cevap
            <div class="cevaplar">
              <input type="radio" id="A" name="cevap" value="A" required />
              <label for="A">A</label>
              <input type="radio" id="B" name="cevap" value="B" required />
              <label for="B">B</label>
              <input type="radio" id="C" name="cevap" value="C" required />
              <label for="C">C</label>
              <input type="radio" id="D" name="cevap" value="D" required />
              <label for="D">D</label>
            </div>
          </div>
        </div>
        <br />
        <div>
          <a href="index.php">
            <button type="button" class="geriBtn" style="margin-right: 20px">
              Geri Dön
            </button></a>
          <button type="submit" class="ekleBtn">Ekle</button>
        </div>
      </form>
    </div>
  </body>
</html>
