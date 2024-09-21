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

$stmt = $pdo->prepare("SELECT id, name FROM restaurant WHERE company_id = :company_id");
$stmt->execute(['company_id' => $company_id]);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Yemek Ekle</title>
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
                <h1 class="my-4">Yemek Ekle</h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="restaurant" class="form-label">Restoran Seç</label>
                        <select class="form-control" id="restaurant" name="restaurant">
                            <?php foreach ($restaurants as $restaurant): ?>
                                <option value="<?php echo $restaurant['id']; ?>"><?php echo htmlspecialchars($restaurant['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Yemek Adı</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Ücret (TL)</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">İndirim (%)</label>
                        <input type="number" class="form-control" id="discount" name="discount" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Yemek Resmi</label>
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
    $price = $_POST['price'] ?? null;
    $discount = isset($_POST['discount']) && $_POST['discount'] !== '' ? $_POST['discount'] : null;
    $restaurant_id = $_POST['restaurant'] ?? null;

    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = time() . '_' . $image['name'];
        $imagePath = $imageName;
        move_uploaded_file($image['tmp_name'], '../images/food/' . $imageName);
    }

    $sql = "INSERT INTO food (restaurant_id, name, description, image_path, price, discount, created_at) 
            VALUES (:restaurant_id, :name, :description, :image_path, :price, :discount, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'restaurant_id' => $restaurant_id,
        'name' => $name,
        'description' => $description,
        'image_path' => $imagePath,
        'price' => $price,
        'discount' => $discount
    ]);

    echo "<script>
        Swal.fire({
            title: 'Başarılı!',
            text: 'Yeni yemek eklendi.',
            icon: 'success',
            confirmButtonText: 'Tamam'
        }).then(function() {
            window.location = 'foods.php';
        });
    </script>";
    exit;
}
?>
