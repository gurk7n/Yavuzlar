<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kullanıcı Kaydet</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body><?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $company_id = !empty($_POST['company_id']) ? $_POST['company_id'] : null;
    $role = !empty($_POST['role']) ? $_POST['role'] : null;
    $name = !empty($_POST['name']) ? $_POST['name'] : null;
    $surname = !empty($_POST['surname']) ? $_POST['surname'] : null;
    $username = !empty($_POST['username']) ? $_POST['username'] : null;
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    $balance = !empty($_POST['balance']) ? $_POST['balance'] : null;
    $image_path = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDir = '../../images/user/';
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image_path = $fileName;
            }
        }
    }

    $setClauses = [];
    $params = ['id' => $id];

    if ($company_id !== null) {
        $setClauses[] = "company_id = :company_id";
        $params['company_id'] = $company_id;
    }
    if ($role !== null) {
        $setClauses[] = "role = :role";
        $params['role'] = $role;
    }
    if ($name !== null) {
        $setClauses[] = "name = :name";
        $params['name'] = $name;
    }
    if ($surname !== null) {
        $setClauses[] = "surname = :surname";
        $params['surname'] = $surname;
    }
    if ($username !== null) {
        $setClauses[] = "username = :username";
        $params['username'] = $username;
    }
    if ($balance !== null) {
        $setClauses[] = "balance = :balance";
        $params['balance'] = $balance;
    }
    if ($password !== null) {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
        $setClauses[] = "password = :password";
        $params['password'] = $hashedPassword;
    }
    if ($image_path !== null) {
        $setClauses[] = "image_path = :image_path";
        $params['image_path'] = $image_path;
    }

    if (empty($setClauses)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Hata!',
                    text: 'Güncellenmesi gereken bir alan bulunamadı.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    window.location.href = '../users.php';
                });
              </script>";
        exit;
    }

    try {
        $sql = "UPDATE users SET " . implode(", ", $setClauses) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Kullanıcı başarıyla güncellendi.',
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
} else {
    header("Location: ../users.php");
    exit;
}
?>


</body>
</html>
