<?php
require '../db.php';
require 'check.php';

$commentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($commentId > 0) {
    $stmt = $pdo->prepare("SELECT user_id FROM comments WHERE id = :id");
    $stmt->execute(['id' => $commentId]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($comment) {
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $comment['user_id']) {
            $stmt = $pdo->prepare("UPDATE comments SET deleted_at = NOW() WHERE id = :id");
            $stmt->execute(['id' => $commentId]);

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo 'Bu yorumu silmek için yetkiniz yok.';
            exit;
        }
    } else {
        echo 'Yorum bulunamadı.';
        exit;
    }
} else {
    echo 'Geçersiz yorum ID.';
    exit;
}
?>
