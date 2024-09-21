<?php
require '../../db.php';
require 'check.php';

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$image = $_FILES['image'] ?? null;

if ($id) {
    try {
        if ($username) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username AND id != :id");
            $stmt->execute(['username' => $username, 'id' => $id]);
            $existingUsernameCount = $stmt->fetchColumn();

            if ($existingUsernameCount > 0) {
                $_SESSION['update_error'] = 'Bu kullanıcı adı zaten başka bir kullanıcı tarafından kullanılıyor.';
                header('Location: ../settings.php');
                exit();
            }
        }

        $updateFields = [];
        $params = ['id' => $id];
        
        if ($name) {
            $updateFields[] = 'name = :name';
            $params['name'] = $name;
        }
        
        if ($surname) {
            $updateFields[] = 'surname = :surname';
            $params['surname'] = $surname;
        }
        
        if ($username) {
            $updateFields[] = 'username = :username';
            $params['username'] = $username;
        }

        if ($password) {
            $updateFields[] = 'password = :password';
            $params['password'] = password_hash($password, PASSWORD_ARGON2ID);
        }
        
        if ($image && $image['error'] == UPLOAD_ERR_OK) {
            $imagePath = basename($image['name']);
            move_uploaded_file($image['tmp_name'], '../../images/user/' . $imagePath);
            $updateFields[] = 'image_path = :image_path';
            $params['image_path'] = $imagePath;
        }

        if ($updateFields) {
            $query = 'UPDATE users SET ' . implode(', ', $updateFields) . ' WHERE id = :id';
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            $_SESSION['update_success'] = true;
        }
        
    } catch (Exception $e) {
        $_SESSION['update_error'] = 'Güncelleme sırasında bir hata oluştu: ' . $e->getMessage();
    }
    
    header('Location: ../settings.php');
    exit();
}
