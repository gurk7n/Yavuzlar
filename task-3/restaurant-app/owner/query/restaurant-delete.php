<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Restoran Sil</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Geçersiz restoran ID.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../restaurants.php';
            });
          </script>";
    exit;
}

$id = (int)$_GET['id'];

$checkStmt = $pdo->prepare("SELECT COUNT(*) FROM restaurant WHERE id = :id");
$checkStmt->execute(['id' => $id]);
$count = $checkStmt->fetchColumn();

if ($count == 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Restoran bulunamadı.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../restaurants.php';
            });
          </script>";
    exit;
}


try {
    $deleteStmt = $pdo->prepare("UPDATE restaurant SET deleted_at = NOW() WHERE id = :id");
    $deleteStmt->execute(['id' => $id]);

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Başarılı!',
                text: 'Restoran başarıyla silindi.',
                icon: 'success',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../restaurants.php';
            });
          </script>";
} catch (PDOException $e) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Bir hata oluştu: " . $e->getMessage() . "',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../restaurants.php';
            });
          </script>";
}
?>

</body>
</html>