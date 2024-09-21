<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !isset($user['role'])) {
    echo "<script>window.location.href = '../../login.php';</script>";
    exit;
}

switch ($user['role']) {
    case 'admin':
        break;
    case 'user':
        echo "<script>window.location.href = '../../index.php';</script>";
        exit;
    case 'owner':
        echo "<script>window.location.href = '../../owner/index.php';</script>";
        exit;
    default:
        echo "<script>window.location.href = '../../login.php';</script>";
        exit;
}
?>