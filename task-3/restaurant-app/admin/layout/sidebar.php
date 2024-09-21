
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
      <a class="navbar-brand ps-3" href="index.php">Restoran</a>
      <button
        class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
        id="sidebarToggle"
        href="#!"
      >
        <i class="fas fa-bars"></i>
      </button>
        <div class="input-group">
        </div>
      <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            id="navbarDropdown"
            href="#"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            ><i class="fas fa-user fa-fw"></i
          ></a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item"><?php echo $_SESSION['username']; ?></a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="../../query/logout.php">Çıkış Yap</a></li>
          </ul>
        </li>
      </ul>
    </nav>
    <div id="layoutSidenav">
      <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
          <div class="sb-sidenav-menu">
            <div class="nav">
              <div class="sb-sidenav-menu-heading">GİRİŞ</div>
              <a class="nav-link" href="index.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-tachometer-alt"></i>
                </div>
                Admin Paneli
              </a>
              <div class="sb-sidenav-menu-heading">YÖNETİM</div>
               <a class="nav-link" href="users.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-users"></i>
                </div>
                Kullanıcı Yönetimi
              </a>
              <a class="nav-link" href="orders.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                Sipariş Yönetimi
              </a>
              <a class="nav-link" href="restaurants.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-utensils"></i>
                </div>
                Restoran Yönetimi
              </a>
              <a class="nav-link" href="companies.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-building"></i>
                </div>
                Firma Yönetimi
              </a>
              <a class="nav-link" href="foods.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-hamburger"></i>
                </div>
                Yemek Yönetimi
              </a>
              <a class="nav-link" href="coupons.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-ticket-alt"></i>
                </div>
                Kupon Yönetimi
              </a>
              <a class="nav-link" href="comments.php">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-comments"></i>
                </div>
                Yorum Yönetimi
              </a>
            </div>
          </div>
          <div class="sb-sidenav-footer">
            <img src="../images/yavuzlar.png" class="img-fluid">
          </div>
        </nav>
      </div>