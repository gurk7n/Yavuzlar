<?php
require '../../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Firma Kaydet</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body><?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = !empty($_POST['name']) ? $_POST['name'] : null;
    $description = !empty($_POST['description']) ? $_POST['description'] : null;
    $logo_path = !empty($_FILES['logo']['name']) ? $_FILES['logo']['name'] : null;

    $setClauses = [];
    $params = ['id' => $id];

    if ($name !== null) {
        $setClauses[] = "name = :name";
        $params['name'] = $name;
    }
    if ($description !== null) {
        $setClauses[] = "description = :description";
        $params['description'] = $description;
    }
    if ($logo_path !== null) {
        $uploadDir = '../../images/company/';
        $uploadFile = $uploadDir . basename($logo_path);
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
            $setClauses[] = "logo_path = :logo_path";
            $params['logo_path'] = $logo_path;
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Logo yüklenemedi.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    }).then(() => {
                        window.location.href = '../companies.php';
                    });
                  </script>";
            exit;
        }
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
                    window.location.href = '../companies.php';
                });
              </script>";
        exit;
    }

    try {
        $sql = "UPDATE company SET " . implode(", ", $setClauses) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Firma başarıyla güncellendi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    window.location.href = '../companies.php';
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
                    window.location.href = '../companies.php';
                });
              </script>";
    }
} else {
    header("Location: ../companies.php");
    exit;
}
?>

</body>
</html>
