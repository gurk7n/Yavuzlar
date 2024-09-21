<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Giriş Yap</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  </head>
  <body class="food-background">
    <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
        <main>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                  <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Giriş Yap</h3>
                  </div>
                  <div class="card-body">
                    <form action="query/login.php" method="POST">
                      <div class="form-floating mb-3">
                        <input name="username" class="form-control" id="inputUsername" type="text" placeholder="Kullanıcı adı" required />
                        <label for="inputUsername">Kullanıcı adı</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input name="password" class="form-control" id="inputPassword" type="password" placeholder="Parola" required />
                        <label for="inputPassword">Parola</label>
                      </div>
                      <div class="d-flex justify-content-center mt-4 mb-0">
                        <button class="btn btn-primary btn-block" type="submit">Giriş Yap</button>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer text-center py-3">
                    <div class="small">
                      <a href="register.php">Bir hesabın yok mu? Kayıt ol!</a>
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
                <a href="#">Gizlilik Politikası</a> &middot;
                <a href="#">Şartlar &amp; Koşullar</a>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>
