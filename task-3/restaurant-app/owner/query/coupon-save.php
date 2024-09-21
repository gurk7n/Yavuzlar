<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kupon Ekle</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
$restaurant_id = $_POST['restaurant_id'] ?? null;
$name = $_POST['name'] ?? null;
$discount = $_POST['discount'] ?? null;

if (!$restaurant_id || !$name || !$discount) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Hata',
            text: 'Lütfen tüm alanları doldurun!'
        }).then(() => {
            window.location.href = '../coupons.php';
        });
    </script>";
    exit;
}

$sql = "INSERT INTO cupon (restaurant_id, name, discount, created_at) VALUES (:restaurant_id, :name, :discount, NOW())";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([
    'restaurant_id' => $restaurant_id,
    'name' => $name,
    'discount' => $discount
]);

if ($success) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Başarılı',
            text: 'Kupon başarıyla eklendi!'
        }).then(() => {
            window.location.href = '../coupons.php';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Hata',
            text: 'Kupon eklenirken bir hata oluştu!'
        }).then(() => {
            window.location.href = '../coupons.php';
        });
    </script>";
}
?>
</body>
</html>
