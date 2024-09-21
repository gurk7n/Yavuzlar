<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Firma Ekle</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body><?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = !empty($_POST['name']) ? $_POST['name'] : null;
    $description = !empty($_POST['description']) ? $_POST['description'] : null;
    $logo_path = null;

    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../../images/company/";
        $logoFileName = basename($_FILES["logo"]["name"]);
        $targetFilePath = $targetDir . $logoFileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFilePath)) {
                $logo_path = $logoFileName;
            }
        }
    }

    if ($name && $description) {
        $stmt = $pdo->prepare("INSERT INTO company (name, description, logo_path) VALUES (:name, :description, :logo_path)");
        $stmt->execute(['name' => $name, 'description' => $description, 'logo_path' => $logo_path]);

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Firma başarıyla eklendi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    window.location.href = '../companies.php';
                });
              </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Hata!',
                    text: 'Lütfen tüm alanları doldurun.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    window.location.href = '../company-add.php';
                });
              </script>";
    }
}
?>
</body>
</html>