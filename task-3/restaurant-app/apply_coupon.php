<?php
require 'db.php';
require 'query/check.php';

if (isset($_GET['coupon'])) {
    $coupon = $_GET['coupon'];

    $stmtCoupon = $pdo->prepare("SELECT discount FROM cupon WHERE name = :name AND deleted_at IS NULL");
    $stmtCoupon->execute([':name' => $coupon]);
    $cuponData = $stmtCoupon->fetch(PDO::FETCH_ASSOC);

    if ($cuponData) {
        echo json_encode(['success' => true, 'discount' => $cuponData['discount']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kupon kodu.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Kupon kodu girilmedi.']);
}
