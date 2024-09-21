<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Restoran Kaydet</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body><?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $name = !empty($_POST['name']) ? $_POST['name'] : null;
    $description = !empty($_POST['description']) ? $_POST['description'] : null;
    $image_path = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../../images/restaurant/";
        $imageFileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageFileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image_path = $imageFileName;
            }
        }
    }

    if ($id && $name && $description) {
        $stmt = $pdo->prepare("UPDATE restaurant SET name = :name, description = :description, image_path = COALESCE(:image_path, image_path) WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'image_path' => $image_path
        ]);

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Restoran başarıyla güncellendi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    window.location.href = '../restaurants.php';
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
                    window.location.href = '../restaurant-edit.php?id={$id}';
                });
              </script>";
    }
}
?>
</body>
</html>
