<?php
require '../../db.php';
require 'check.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $logo = $_FILES['logo'] ?? null;
    $logo_path = '';

    if (empty($name)) {
        $errors[] = 'Firma adı boş bırakılamaz.';
    }

    if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../images/company/";
        $fileName = basename($logo['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileType), $allowTypes)) {
            $errors[] = 'Sadece JPG, JPEG, PNG ve GIF dosyalarına izin veriliyor.';
        } else {
            if (move_uploaded_file($logo['tmp_name'], $targetFilePath)) {
                $logo_path = $fileName;
            } else {
                $errors[] = 'Logo yüklenirken bir hata oluştu.';
            }
        }
    }

    if (empty($errors)) {
        if (!empty($logo_path)) {
            $stmt = $pdo->prepare("UPDATE company SET name = :name, description = :description, logo_path = :logo_path WHERE id = :company_id");
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'logo_path' => $logo_path,
                'company_id' => $company_id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE company SET name = :name, description = :description WHERE id = :company_id");
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'company_id' => $company_id
            ]);
        }

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Düzenle</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (!empty($errors)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    html: '<?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>',
                    confirmButtonText: 'Tamam'
                });
            <?php elseif ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Firma bilgileri başarıyla güncellendi.',
                    confirmButtonText: 'Tamam'
                }).then(function() {
                    window.location.href = '../company.php';
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
