<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Firma Aktifleştir</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Geçersiz firma ID.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../companies.php';
            });
          </script>";
    exit;
}

$id = (int)$_GET['id'];

$checkStmt = $pdo->prepare("SELECT COUNT(*) FROM company WHERE id = :id");
$checkStmt->execute(['id' => $id]);
$count = $checkStmt->fetchColumn();

if ($count == 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Firma bulunamadı.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../companies.php';
            });
          </script>";
    exit;
}

$id = (int)$_GET['id'];

try {
    $activateStmt = $pdo->prepare("UPDATE company SET deleted_at = NULL WHERE id = :id");
    $activateStmt->execute(['id' => $id]);

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Başarılı!',
                text: 'Firma başarıyla aktif hale getirildi.',
                icon: 'success',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../companies.php';
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
                window.location.href = '../companies.php';
            });
          </script>";
}
?>
</body>
</html>