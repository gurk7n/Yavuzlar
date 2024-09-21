<?php
require '../db.php';
require 'query/check.php';

$userStmt = $pdo->prepare("SELECT company_id, created_at FROM users WHERE id = :user_id");
$userStmt->execute([':user_id' => $user_id]);
$userData = $userStmt->fetch(PDO::FETCH_ASSOC);

$company_id = $userData['company_id'];
$registrationDate = $userData['created_at'];

$restaurantCountStmt = $pdo->prepare("SELECT COUNT(*) FROM restaurant WHERE company_id = :company_id");
$restaurantCountStmt->execute([':company_id' => $company_id]);
$totalRestaurants = $restaurantCountStmt->fetchColumn();

$commentsCountStmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE restaurant_id IN (SELECT id FROM restaurant WHERE company_id = :company_id)");
$commentsCountStmt->execute([':company_id' => $company_id]);
$totalComments = $commentsCountStmt->fetchColumn();

$averageScoreStmt = $pdo->prepare("SELECT AVG(score) FROM comments WHERE restaurant_id IN (SELECT id FROM restaurant WHERE company_id = :company_id)");
$averageScoreStmt->execute([':company_id' => $company_id]);
$averageScore = $averageScoreStmt->fetchColumn();

try {
    $commentsStmt = $pdo->prepare("SELECT c.id, c.title, c.description, c.score, c.created_at, r.name AS restaurant_name
                                    FROM comments c
                                    JOIN restaurant r ON c.restaurant_id = r.id
                                    WHERE r.company_id = :company_id AND c.deleted_at IS NULL
                                    ORDER BY c.created_at DESC
                                    LIMIT 5");
    $commentsStmt->execute([':company_id' => $company_id]);
    $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Bir hata oluştu: " . $e->getMessage() . "',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Firma Paneli</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="my-4">Firma Paneli</h1>
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6">
              <div class="card bg-primary text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-wallet fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Toplam Restoran</h5>
                    <p class="card-text fs-4"><?php echo $totalRestaurants; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-receipt fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Toplam Yorum</h5>
                    <p class="card-text fs-4"><?php echo $totalComments; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="card bg-success text-white mb-4">
                  <div class="card-body d-flex align-items-center">
                      <i class="fas fa-comments fs-2 me-4"></i>
                      <div>
                          <h5 class="card-title">Ortalama Puan</h5>
                          <p class="card-text fs-4"><?php echo $averageScore !== null ? number_format($averageScore, 2) : 'Puan yok'; ?></p>
                      </div>
                  </div>
              </div>
          </div>
            <div class="col-lg-3 col-md-6">
              <div class="card bg-indigo text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-calendar-days fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Kayıt Tarihi</h5>
                    <p class="card-text fs-4"><?php echo htmlspecialchars(date('d-m-Y', strtotime($registrationDate))); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row px-4">
        <div class="col-xl-12">
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span><i class="fas fa-table me-1"></i> Son Yorumlar</span>
              <a href="comments.php" class="btn btn-link">Tümünü Gör</a>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Restoran</th>
                    <th>Başlık</th>
                    <th>Yorum</th>
                    <th>Puan</th>
                    <th>Tarih</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($comments as $comment): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($comment['id']); ?></td>
                    <td><?php echo htmlspecialchars($comment['restaurant_name']); ?></td>
                    <td><?php echo htmlspecialchars($comment['title']); ?></td>
                    <td><?php echo htmlspecialchars($comment['description']); ?></td>
                    <td><?php echo htmlspecialchars($comment['score']); ?></td>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($comment['created_at']))); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php require '../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
  </body>
</html>
