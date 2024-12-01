<?php
session_start();
?>

<?php
require 'config.php';
require 'model/MenuModel.php';
require 'controller/MenuController.php';
require 'model/ContentModel.php';

// Menük betöltése az adatbázisból
$query = "SELECT id, name, page_slug, parent_id FROM web2_menus ORDER BY parent_id ASC, id ASC";
$result = $db->query($query);

if (!$result) {
    die("Adatbázis hiba: " . $db->error);
}

$menus = $result->fetch_all(MYSQLI_ASSOC);

$contentModel = new ContentModel($db);
$aboutText = $contentModel->getContent('about_section');
$isAdmin = isset($_SESSION['username']) && $_SESSION['username'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Andor-Viktor - Weboldal</title>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <h1 class="sitename">Főoldal</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <?php foreach ($menus as $menu): ?>
            <?php if (empty($menu['parent_id'])): // Csak a gyökér menüelemeket jelenítse meg ?>
              <li>
                <a href="<?= htmlspecialchars($menu['page_slug']) ?>" 
                   class="<?= basename($_SERVER['PHP_SELF']) === $menu['page_slug'] ? 'active' : '' ?>">
                   <?= htmlspecialchars($menu['name']) ?>
                </a>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if ($isAdmin): ?>
              <li><a href="curl_test.php">cURL teszt</a></li>
          <?php else: ?>
              <li><a href="no_admin.php">cURL teszt</a></li>
          <?php endif; ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="auth-buttons">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="auth/register.php" class="btn btn-outline-primary">Regisztráció</a>
          <a href="auth/login.php" class="btn btn-primary">Bejelentkezés</a>
        <?php else: ?>
          <a href="auth/logout.php" class="btn btn-danger">Kijelentkezés</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
  
  <section id="hero" class="hero section dark-background">
    <div id="hero-carousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">

      <div class="carousel-item active">
        <div class="carousel-container">
          <h2>Üdvözöljük a weboldalunkon!</h2>
          <p>Ezen a weblapon megtekinthetőek bizonyos meccs információk.</p>
          <p>Továbbá árfolyammal kapcsolatos információkat.</p>
          <a href="soap_client.php" class="btn-get-started">Tovább a meccs információkhoz</a>
          <a href="nmb.php" class="btn-get-started">Tovább az árfolyamokhoz</a>
        </div>
      </div>

      <div class="carousel-item">
        <div class="carousel-container">
          <h2 class="animate__animated animate__fadeInDown">SOAP</h2>
          <p class="animate__animated animate__fadeInUp">SOAP webszolgáltatással lett megoldva a Meccsek és az NMB adatok kiszolgálása.</p>
        </div>
      </div>

      <div class="carousel-item">
        <div class="carousel-container">
          <h2 class="animate__animated animate__fadeInDown">
            Restful
          </h2>
          <p class="animate__animated animate__fadeInUp">Restful webszolgáltatással lett megoldva a Meccsek Szerkesztése. GET, POST, DELETE, PUT metódusokat lehet benne használni.</p>
        </div>
      </div>

      <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
      </a>

      <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
      </a>

    </div>

    <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
      <defs>
        <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
      </defs>
      <g class="wave1">
        <use xlink:href="#wave-path" x="50" y="3"></use>
      </g>
      <g class="wave2">
        <use xlink:href="#wave-path" x="50" y="0"></use>
      </g>
      <g class="wave3">
        <use xlink:href="#wave-path" x="50" y="9"></use>
      </g>
    </svg>

  </section>

  <main class="main">
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up">
        <h2>Bemutatkozás</h2>
        <p>Az oldalt Magyarosi Andor Máté és Kaszás Viktor készítette. </p>
        <div class="row gy-4">
          <div class="col-lg-6">
            <p>Az oldal a Selecao templatet használja.</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container">
      <h3>Webprogramozás II</h3>
      <p>A tudás itt kezdődik...</p>
      <div class="social-links">
        <a href="#"><i class="bi bi-twitter"></i></a>
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
      </div>
    </div>
  </footer>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <script src="assets/js/main.js"></script>
</body>
</html>
