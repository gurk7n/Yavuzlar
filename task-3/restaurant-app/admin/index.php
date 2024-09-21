<?php
require '../db.php';
require 'layout/check.php';

$totalUsers = $totalOrders = $totalRestaurants = $totalCompanies = 0;

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM `order`");
    $totalOrders = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM restaurant");
    $totalRestaurants = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM company");
    $totalCompanies = $stmt->fetchColumn();
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
    <title>Restoran - Admin Paneli</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script
      src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php' ?>
      <div id="layoutSidenav_content">
        <main>
        <div class="container-fluid px-4">
        <h1 class="my-4">Admin Paneli</h1>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-users fs-2 me-4"></i>
                            <div>
                                <h5 class="card-title">Toplam Kullanıcı</h5>
                                <p class="card-text fs-4" id="total-users"><?php echo $totalUsers; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-shopping-cart fs-2 me-4"></i>
                            <div>
                                <h5 class="card-title">Toplam Sipariş</h5>
                                <p class="card-text fs-4" id="total-orders"><?php echo $totalOrders; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-utensils fs-2 me-4"></i>
                            <div>
                                <h5 class="card-title">Toplam Restoran</h5>
                                <p class="card-text fs-4" id="total-restaurants"><?php echo $totalRestaurants; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-indigo text-white mb-4">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-building fs-2 me-4"></i>
                            <div>
                                <h5 class="card-title">Toplam Firma</h5>
                                <p class="card-text fs-4" id="total-companies"><?php echo $totalCompanies; ?></p>
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
                <span><i class="fas fa-table me-1"></i> Son Siparişler</span>
                <a href="orders.php" class="btn btn-link">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı Adı</th>
                            <th>Sipariş Durumu</th>
                            <th>Ücret</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $ordersStmt = $pdo->query("SELECT order.id, users.username, order.order_status, order.total_price, order.created_at
                                                       FROM `order`
                                                       JOIN users ON order.user_id = users.id
                                                       ORDER BY order.created_at DESC
                                                       LIMIT 5");
                            $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($orders as $order):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
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
                <span><i class="fas fa-table me-1"></i> Yeni Kullanıcılar</span>
                <a href="users.php" class="btn btn-link">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>Soyad</th>
                            <th>Kullanıcı Adı</th>
                            <th>Kayıt Tarihi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $usersStmt = $pdo->query("SELECT id, name, surname, username, created_at
                                                      FROM users
                                                      ORDER BY created_at DESC
                                                      LIMIT 5");
                            $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($users as $user):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['surname']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($user['created_at']))); ?></td>
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
<?php require 'layout/footer.php' ?>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="../js/scripts.js"></script>
  </body>
</html>
