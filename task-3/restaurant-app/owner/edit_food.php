<?php
require '../db.php';
require 'query/check.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: foods.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM food WHERE id = :id");
$stmt->execute(['id' => $id]);
$food = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$food) {
    echo "Yemek bulunamadı.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Yemek Düzenle</title>
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
                <h1 class="my-4">Yemek Düzenle</h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Yemek Adı</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($food['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($food['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Ücret (TL)</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($food['price']); ?>" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">İndirim (%)</label>
                        <input type="number" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($food['discount']); ?>" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Mevcut Resim</label>
                        <?php if ($food['image_path']): ?>
                            <div class="mb-2">
                                <img src="../images/food/<?php echo htmlspecialchars($food['image_path']); ?>" alt="Food Image" style="width: 100px; height: auto;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
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
    $price = $_POST['price'] ?? null;
    $discount = $_POST['discount'] ?? null;
    $imagePath = $food['image_path'];

    $fieldsToUpdate = [];
    $params = ['id' => $id];

    if (!empty($name)) {
        $fieldsToUpdate[] = "name = :name";
        $params['name'] = $name;
    }

    if (!empty($description)) {
        $fieldsToUpdate[] = "description = :description";
        $params['description'] = $description;
    }

    if (!empty($price)) {
        $fieldsToUpdate[] = "price = :price";
        $params['price'] = $price;
    }

    if (!empty($discount)) {
        $fieldsToUpdate[] = "discount = :discount";
        $params['discount'] = $discount;
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = time() . '_' . $image['name'];
        $imagePath = $imageName;
        move_uploaded_file($image['tmp_name'], '../images/food/' . $imageName);
        $fieldsToUpdate[] = "image_path = :image_path";
        $params['image_path'] = $imagePath;
    }

    if (!empty($fieldsToUpdate)) {
        $sql = "UPDATE food SET " . implode(', ', $fieldsToUpdate) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo "<script>
            Swal.fire({
                title: 'Başarılı!',
                text: 'Yemek güncellendi.',
                icon: 'success',
                confirmButtonText: 'Tamam'
            }).then(function() {
                window.location = 'foods.php';
            });
        </script>";
        exit;
    } else {
        echo "<script>
            Swal.fire({
                title: 'Hata!',
                text: 'Güncellenecek alan seçilmedi.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
        </script>";
    }
}
?>