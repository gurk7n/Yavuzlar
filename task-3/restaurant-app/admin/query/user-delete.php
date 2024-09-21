<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kullanıcı Sil</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Geçersiz kullanıcı ID.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../users.php';
            });
          </script>";
    exit;
}

$id = (int)$_GET['id'];

$checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
$checkStmt->execute(['id' => $id]);
$count = $checkStmt->fetchColumn();

if ($count == 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Kullanıcı bulunamadı.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../users.php';
            });
          </script>";
    exit;
}


try {
    $deleteStmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
    $deleteStmt->execute(['id' => $id]);

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Başarılı!',
                text: 'Kullanıcı başarıyla silindi.',
                icon: 'success',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../users.php';
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
                window.location.href = '../users.php';
            });
          </script>";
}
?>

</body>
</html>