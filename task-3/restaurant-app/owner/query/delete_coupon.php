<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kupon Sil</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php
$coupon_id = $_POST['coupon_id'] ?? null;

if ($coupon_id) {
    $sql = "UPDATE cupon SET deleted_at = NOW() WHERE id = :coupon_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['coupon_id' => $coupon_id]);

    if ($stmt->rowCount()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Başarılı!',
                text: 'Kupon başarıyla silindi.',
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
                text: 'Kupon silinirken bir hata oluştu.',
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
?>
</body>
</html>