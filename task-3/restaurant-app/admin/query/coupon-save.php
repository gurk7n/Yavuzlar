<?php
require '../../db.php';
require 'check.php';

$restaurant_id = $_POST['restaurant_id'] ?? null;
$name = $_POST['name'] ?? '';
$discount = $_POST['discount'] ?? 0;

if (empty($name) || $discount < 0 || $discount > 100) {
    echo "Geçersiz veri.";
    exit;
}

$sql = "INSERT INTO cupon (restaurant_id, name, discount) VALUES (:restaurant_id, :name, :discount)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'restaurant_id' => $restaurant_id === '' ? null : $restaurant_id,
    'name' => $name,
    'discount' => $discount
]);

header("Location: ../coupons.php");
exit;
