<?php
require 'db.php';
require 'query/check.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id AND deleted_at IS NULL");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $current_balance = $user['balance'];
    } else {
        header('Location: error.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? 0;
    
    if (is_numeric($amount) && $amount > 0) {
        $new_balance = $current_balance + $amount;

        $stmt = $pdo->prepare("UPDATE users SET balance = :balance WHERE id = :id");
        $stmt->execute([
            'balance' => $new_balance,
            'id' => $user_id
        ]);

        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_balance = $user['balance'];

        header('Location: balance.php?status=success');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Bakiye Yükle</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <style>
    .balance-form {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .balance-form input[type="number"] {
      width: 100%;
    }
  </style>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center my-4">
          <h1 class="my-0">Bakiye Yükle</h1>
        </div>

        <div class="balance-form">
          <p>Mevcut Bakiyeniz: <strong><?php echo htmlspecialchars($current_balance); ?> TL</strong></p>
          
          <form method="POST" action="">
            <div class="mb-3">
              <label for="amount" class="form-label">Yüklemek İstediğiniz Miktar (TL)</label>
              <input type="number" class="form-control" id="amount" name="amount" value="100" min="0" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary">Bakiye Ekle</button>
          </form>
        </div>
      </div>
    </main>
    <?php require 'layout/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
