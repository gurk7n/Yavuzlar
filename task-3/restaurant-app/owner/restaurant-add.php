<?php
require '../db.php';
require 'query/check.php';

$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !$user['company_id']) {
    echo "Firma bilgisi bulunamadı.";
    exit;
}

$company_id = $user['company_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Restoran Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="my-4">Restoran Ekle</h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Restoran Adı</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Restoran Resmi</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ekle</button>
                </form>
            </div>
        </main>
        <?php require '../layout/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.js" crossorigin="anonymous"></script>
    <script src="../js/datatables/datatables-simple-demo.js"></script>
</body>
</html>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $description = $_POST['description'] ?? null;
    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = time() . '_' . $image['name'];
        $imagePath = $imageName;
        move_uploaded_file($image['tmp_name'], '../images/restaurant/' . $imageName);
    }

    $sql = "INSERT INTO restaurant (company_id, name, description, image_path, created_at) 
            VALUES (:company_id, :name, :description, :image_path, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'company_id' => $company_id,
        'name' => $name,
        'description' => $description,
        'image_path' => $imagePath
    ]);

    echo "<script>
        Swal.fire({
            title: 'Başarılı!',
            text: 'Yeni restoran eklendi.',
            icon: 'success',
            confirmButtonText: 'Tamam'
        }).then(function() {
            window.location = 'restaurants.php';
        });
    </script>";
    exit;
}
?>