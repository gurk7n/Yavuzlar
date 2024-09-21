<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kayıt Ol</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script
      src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </head>
  <body class="food-background">
    <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
        <main>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                  <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">
                      Kayıt Ol
                    </h3>
                  </div>
                  <div class="card-body">
                    <form action="register.php" method="POST">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <div class="form-floating mb-3 mb-md-0">
                            <input
                              name="name"
                              class="form-control"
                              id="inputFirstName"
                              type="text"
                              placeholder="Ad"
                              required
                            />
                            <label for="inputFirstName">Ad</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input
                              name="surname"
                              class="form-control"
                              id="inputLastName"
                              type="text"
                              placeholder="Soyad"
                              required
                            />
                            <label for="inputLastName">Soyad</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-floating mb-3">
                        <input
                          name="username"
                          class="form-control"
                          id="inputUsername"
                          type="text"
                          placeholder="Kullanıcı adı"
                          required
                        />
                        <label for="inputUsername">Kullanıcı adı</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input
                        name="password"
                          class="form-control"
                          id="inputPassword"
                          type="password"
                          placeholder="Parola"
                          required
                        />
                        <label for="inputPassword">Parola</label>
                      </div>
                      <div class="mt-4 mb-0">
                        <div class="d-grid">
                        <button class="btn btn-primary btn-block" type="submit">Hesap Oluştur</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer text-center py-3">
                    <div class="small">
                      <a href="login.php">Bir hesabın var mı? Giriş Yap</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
      <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
          <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
              <img src="images/yavuzlar-siyah.png" width="150px">
              <div>
                <a href="#">Privacy Policy</a>
                &middot;
                <a href="#">Terms &amp; Conditions</a>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="js/scripts.js"></script>
  </body>
</html>

<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $userExists = $stmt->fetchColumn();

        if ($userExists > 0) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Bu kullanıcı adı zaten alınmış.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                  </script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

            $stmt = $pdo->prepare("INSERT INTO users (role, name, surname, username, password) VALUES (:role, :name, :surname, :username, :password)");
            $stmt->execute([
                ':role' => 'user',
                ':name' => $name,
                ':surname' => $surname,
                ':username' => $username,
                ':password' => $hashed_password
            ]);

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: 'Kayıt Başarılı!',
                        text: 'Hesabınız başarıyla oluşturuldu.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    }).then(function() {
                        window.location.href = 'login.php';
                    });
                  </script>";
        }
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
}
?>