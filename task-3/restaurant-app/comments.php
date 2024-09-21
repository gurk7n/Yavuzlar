<?php
require 'db.php';
require 'query/check.php';

$restaurantId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($restaurantId > 0) {
    $stmtRestaurant = $pdo->prepare("SELECT name FROM restaurant WHERE id = :restaurant_id");
    $stmtRestaurant->execute([':restaurant_id' => $restaurantId]);
    $restaurant = $stmtRestaurant->fetch(PDO::FETCH_ASSOC);
    
    $stmtComments = $pdo->prepare("
        SELECT c.id, c.title, c.description, c.score, c.created_at, u.username, u.image_path, u.id as user_id
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.deleted_at IS NULL AND c.restaurant_id = :restaurant_id
        ORDER BY c.created_at DESC
    ");
    $stmtComments->execute([':restaurant_id' => $restaurantId]);
    $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo 'Geçersiz restoran ID.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Yorumlar</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
      <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="my-0"><?php echo htmlspecialchars($restaurant['name']); ?> Yorumları</h1>
        <a href="add-comment.php?id=<?php echo htmlspecialchars($restaurantId)?>" class="btn btn-success">Yorum Ekle</a>
      </div>
      <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
          <?php 
            $userImagePath = !empty($comment['image_path']) ? '/images/user/' . $comment['image_path'] : '/images/user/default.jpg';
            $formattedDate = date('d-m-Y', strtotime($comment['created_at']));
            $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $comment['user_id'];
          ?>
          <div class="comment-card">
            <img src="<?php echo htmlspecialchars($userImagePath); ?>" alt="<?php echo htmlspecialchars($comment['username']); ?>">
            <div class="content">
              <div class="title"><?php echo htmlspecialchars($comment['title']); ?></div>
              <div class="description"><?php echo htmlspecialchars($comment['description']); ?></div>
              <div class="score">Puan: <?php echo htmlspecialchars($comment['score']); ?></div>
              <div class="username"><?php echo htmlspecialchars($comment['username']); ?></div>
              <div class="date"><?php echo htmlspecialchars($formattedDate); ?></div>
              
              <?php if ($isOwner): ?>
                <div class="actions my-2">
                  <a href="edit-comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-warning me-1">Düzenle</a>
                  <a href="query/delete-comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-danger" onclick="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">Sil</a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Bu restorana ait yorum bulunmuyor.</p>
      <?php endif; ?>
      </div>
    </main>
    <?php require 'layout/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="../js/scripts.js"></script>
</body>
</html>
