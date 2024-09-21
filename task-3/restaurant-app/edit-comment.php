<?php
require 'db.php';
require 'query/check.php';

$commentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($commentId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = :id AND deleted_at IS NULL");
    $stmt->execute(['id' => $commentId]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        echo 'Yorum bulunamadı.';
        exit;
    }
} else {
    echo 'Geçersiz yorum ID.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Yorum Düzenle</title>
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="my-4">Yorum Düzenle</h1>
        
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($comment['title']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Yorum</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($comment['description']); ?></textarea>
          </div>
          <div class="mb-3">
            <label for="score" class="form-label">Puan (1-10)</label>
            <input type="number" class="form-control" id="score" name="score" min="1" max="10" value="<?php echo htmlspecialchars($comment['score']); ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
      </div>
    </main>
    <?php require 'layout/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>
</html>
<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $score = $_POST['score'] ?? '';

    if ($title && $description && is_numeric($score) && $score >= 1 && $score <= 10) {
        $stmt = $pdo->prepare("UPDATE comments SET title = :title, description = :description, score = :score, updated_at = NOW() WHERE id = :id");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'score' => $score,
            'id' => $commentId
        ]);

        echo '<script>
                Swal.fire({
                    title: "Başarı!",
                    text: "Yorum başarıyla güncellendi.",
                    icon: "success",
                    confirmButtonText: "Tamam"
                }).then(() => {
                    window.location.href = "comments.php?id=' . $comment['restaurant_id'] . '";
                });
              </script>';
        exit();
    } else {
        $error = 'Lütfen tüm alanları doldurun ve puanı 1 ile 10 arasında girin.';
    }
}

?>