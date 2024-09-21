<?php
require 'db.php';
require 'query/check.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Oturum açmadan yorum yapamazsınız.';
    exit;
}

$userId = $_SESSION['user_id'];
$restaurantId = isset($_GET['id']) ? intval($_GET['id']) : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Yorum Ekle</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
    <div class="container">
        <h1 class="my-4">Yorum Ekle</h1>
        <form action="" method="POST">
            <div class="form-group my-4">
                <label for="title">Başlık</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group my-4">
                <label for="description">Yorum</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group my-4">
                <label for="score">Puan (1-10)</label>
                <input type="number" name="score" id="score" class="form-control" min="1" max="10" required>
            </div>
            <button type="submit" class="btn btn-primary">Yorumu Gönder</button>
        </form>
    </div>
    </main> 
    <?php 
    
if ($restaurantId === 0) {
    echo 'Geçersiz restoran ID.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $score = intval($_POST['score']);

    if (empty($title) || empty($description) || $score < 1 || $score > 10) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: 'Lütfen tüm alanları doğru şekilde doldurun!',
            });
        </script>";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO comments (user_id, restaurant_id, title, description, score, created_at) 
            VALUES (:user_id, :restaurant_id, :title, :description, :score, NOW())
        ");
        $result = $stmt->execute([
            ':user_id' => $userId,
            ':restaurant_id' => $restaurantId,
            ':title' => $title,
            ':description' => $description,
            ':score' => $score
        ]);

        if ($result) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı',
                    text: 'Yorum başarıyla eklendi!',
                }).then(function() {
                    window.location.href = 'comments.php?id=$restaurantId';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: 'Yorum eklenirken bir hata oluştu.',
                });
            </script>";
        }
    }
}
    ?>
<?php require 'layout/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
</body>
</html>
