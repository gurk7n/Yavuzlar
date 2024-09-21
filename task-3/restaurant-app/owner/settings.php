<?php
require '../db.php';
require 'query/check.php';

if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $imagePath = '../images/user/' . ($user['image_path'] ?? 'default.jpg');
}

$updateSuccess = $_SESSION['update_success'] ?? false;
$updateError = $_SESSION['update_error'] ?? false;

unset($_SESSION['update_success']);
unset($_SESSION['update_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Ayarlar</title>
    <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
    <?php require 'layout/sidebar.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="my-4">Ayarlar</h1>
                <form method="POST" action="query/user-update.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    
                    <div class="mb-3">
                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profil Resmi" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Yeni Resim Yükle</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Ad</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="surname" class="form-label">Soyad</label>
                        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Yeni Şifre</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </main>
        <?php require '../layout/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        <?php if ($updateSuccess): ?>
        Swal.fire({
            title: 'Başarılı!',
            text: 'Kullanıcı bilgileri başarıyla güncellendi.',
            icon: 'success',
            confirmButtonText: 'Tamam'
        });
        <?php endif; ?>

        <?php if ($updateError): ?>
        Swal.fire({
            title: 'Hata!',
            text: '<?php echo htmlspecialchars($updateError); ?>',
            icon: 'error',
            confirmButtonText: 'Tamam'
        });
        <?php endif; ?>
    </script>
</body>
</html>
