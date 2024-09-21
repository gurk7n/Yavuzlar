<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kupon Aktifleştir</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coupon_id = $_POST['coupon_id'] ?? null;

    if ($coupon_id) {
        $sql = "UPDATE cupon SET deleted_at = NULL WHERE id = :coupon_id";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute(['coupon_id' => $coupon_id])) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Kupon başarıyla aktifleştirildi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../coupons.php';
                    }
                });
            </script>";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Hata!',
                    text: 'Kuponu aktifleştirirken bir hata oluştu.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../coupons.php';
                    }
                });
            </script>";
        }
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Geçersiz kupon ID.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../coupons.php';
                }
            });
        </script>";
    }
} else {
    header('Location: ../coupons.php');
    exit;
}?>
</body>
</html>