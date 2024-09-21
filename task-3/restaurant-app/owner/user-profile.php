<?php
require '../db.php';
require 'query/check.php';

$user_id = $_GET['id'] ?? null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header('Location: error.php');
        exit();
    }

    $imagePath = '../images/user/' . ($user['image_path'] ?? 'default.jpg');
    $createdAt = new DateTime($user['created_at']);
    $formattedDate = $createdAt->format('d M Y');
} else {
    header('Location: error.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Kullanıcı Profili</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <style>
    .profile-card {
      max-width: 600px;
      margin: 0 auto;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .profile-card-header {
      background-color: #f8f9fa;
      padding: 20px;
      text-align: center;
    }
    .profile-card-body {
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .profile-card-body img {
      border-radius: 50%;
      width: 120px;
      height: 120px;
      object-fit: cover;
    }
    .profile-card-body .details {
      flex: 1;
    }
    .profile-card-body .details h5 {
      margin: 0;
      font-size: 1.25rem;
    }
    .profile-card-body .details p {
      margin: 0;
      color: #6c757d;
    }
  </style>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="my-4">Kullanıcı Profili</h1>
        
        <div class="profile-card">
          <div class="profile-card-header">
            <h2><?php echo htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['surname']); ?></h2>
            <p class="text-muted">Kullanıcı Adı: <?php echo htmlspecialchars($user['username']); ?></p>
          </div>
          <div class="profile-card-body">
            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profil Resmi">
            <div class="details">
              <h5>Kayıt Tarihi:</h5>
              <p><?php echo htmlspecialchars($formattedDate); ?></p>
            </div>
          </div>
        </div>
        
      </div>
    </main>
    <?php require '../layout/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
