<?php ob_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Giriş Yap</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<?php
session_start();
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password, role, deleted_at FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !$user['deleted_at'] && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;

        if ($user['role'] === 'admin') {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: 'Giriş Başarılı!',
                        text: 'Başarıyla giriş yaptınız.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/admin/index.php';
                        }
                    });
                  </script>";
        } elseif ($user['role'] === 'owner') {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: 'Giriş Başarılı!',
                        text: 'Başarıyla giriş yaptınız.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/owner/index.php';
                        }
                    });
                  </script>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: 'Giriş Başarılı!',
                        text: 'Başarıyla giriş yaptınız.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/index.php';
                        }
                    });
                  </script>";
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: 'Hata!',
                    text: 'Kullanıcı adı veya parola yanlış.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../login.php';
                        }
                    });
              </script>";
    }
}
?>
</body>
</html>
<?php ob_flush() ?>
