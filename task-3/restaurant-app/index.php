<?php
require 'db.php';
require 'query/check.php';

$stmt = $pdo->prepare("SELECT balance, created_at FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$balance = $user['balance'];
$registrationDate = $user['created_at'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM `order` WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$totalOrders = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE user_id = :user_id AND deleted_at IS NULL");
$stmt->execute([':user_id' => $user_id]);
$totalComments = $stmt->fetchColumn();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Ana Sayfa</title>
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="my-4">Ana Sayfa</h1>
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6">
              <div class="card bg-primary text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-wallet fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Bakiyeniz</h5>
                    <p class="card-text fs-4"><?php echo number_format($balance, 2, ',', '.'); ?>₺</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-receipt fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Siparişleriniz</h5>
                    <p class="card-text fs-4"><?php echo $totalOrders; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="card bg-success text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-comments fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Yorumlarınız</h5>
                    <p class="card-text fs-4"><?php echo $totalComments; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="card bg-indigo text-white mb-4">
                <div class="card-body d-flex align-items-center">
                  <i class="fas fa-calendar-alt fs-2 me-4"></i>
                  <div>
                    <h5 class="card-title">Kayıt Tarihiniz</h5>
                    <p class="card-text fs-4"><?php echo htmlspecialchars(date('d-m-Y', strtotime($registrationDate))); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row px-4">
        <div class="col-xl-6">
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span><i class="fas fa-table me-1"></i> Siparişleriniz</span>
              <a href="#" class="btn btn-link">Tümünü Gör</a>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Sipariş Durumu</th>
                    <th>Ücret</th>
                    <th>Tarih</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  try {
                      $ordersStmt = $pdo->prepare("SELECT id, order_status, total_price, created_at
                                                   FROM `order`
                                                   WHERE user_id = :user_id
                                                   ORDER BY created_at DESC
                                                   LIMIT 5");
                      $ordersStmt->execute([':user_id' => $user_id]);
                      $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

                      foreach ($orders as $order):
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($order['total_price'], 2, ',', '.')) . '₺'; ?></td>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($order['created_at']))); ?></td>
                  </tr>
                  <?php
                      endforeach;
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
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span><i class="fas fa-table me-1"></i> Yorumlarınız</span>
              <a href="#" class="btn btn-link">Tümünü Gör</a>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Yorum</th>
                    <th>Restoran</th>
                    <th>Puan</th>
                    <th>Tarih</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  try {
                      $commentsStmt = $pdo->prepare("SELECT c.id, c.title, c.description, c.score, c.created_at, r.name AS restaurant_name
                                                     FROM comments c
                                                     JOIN restaurant r ON c.restaurant_id = r.id
                                                     WHERE c.user_id = :user_id AND c.deleted_at IS NULL
                                                     ORDER BY c.created_at DESC
                                                     LIMIT 5");
                      $commentsStmt->execute([':user_id' => $user_id]);
                      $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

                      foreach ($comments as $comment):
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($comment['id']); ?></td>
                    <td><?php echo htmlspecialchars($comment['title']); ?></td>
                    <td><?php echo htmlspecialchars($comment['description']); ?></td>
                    <td><?php echo htmlspecialchars($comment['restaurant_name']); ?></td>
                    <td><?php echo htmlspecialchars($comment['score']); ?></td>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($comment['created_at']))); ?></td>
                  </tr>
                  <?php
                      endforeach;
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
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php require 'layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>
