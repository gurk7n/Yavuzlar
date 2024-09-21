<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $basketId = intval($_POST['remove']);
        
        if ($basketId) {
            $stmt = $pdo->prepare("DELETE FROM basket WHERE id = :basket_id");
            $stmt->execute([':basket_id' => $basketId]);
        }

        header('Location: ../basket.php');
        exit;
    } else {
        echo 'Geçersiz sepet ID.';
    }
} else {
    echo 'Geçersiz istek.';
}
?>
