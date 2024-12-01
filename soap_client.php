<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAP Kliens</title>
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 40px 0; 
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .table-header h2 {
            margin: 0;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 10px;
        }
        .btn-scroll {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-scroll:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <h1>Soap Kliens</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php">Főoldal</a></li>
                <li><a href="#about">Bemutatkozás</a></li>
                <li class="dropdown"><a href="#"><span>DB kiszolgáló oldalaink</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="soap_client.php">Meccsek</a></li>
                        <li><a href="nmb.php">NMB Adatok</a></li>
                    </ul>
                </li>
                <li><a href="restful_client.php">Meccsek Szerkesztése</a></li>
                <li><a href="tcpdf.php">Letöltések</a></li>
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

<br><br><br>

<?php
try {
    $client = new SoapClient(null, [
        'location' => "http://andor.ziphost.hu/soap_server.php",
        'uri' => "http://andor.ziphost.hu/soap_server.php",
        'trace' => true
    ]);

    $data = $client->getData();

    echo "<div class='table-header'>";
    echo "<h2 id='belepes-table'>Belepes tábla</h2>";
    echo "<div class='btn-group'>";
    echo "<button class='btn-scroll' onclick=\"document.getElementById('meccs-table').scrollIntoView({ behavior: 'smooth' });\">Ugrás a Meccs táblához</button>";
    echo "<button class='btn-scroll' onclick=\"document.getElementById('nezo-table').scrollIntoView({ behavior: 'smooth' });\">Ugrás a Néző táblához</button>";
    echo "</div>";
    echo "</div>";

    echo "<table>";
    echo "<tr><th>Nezo ID</th><th>Meccs ID</th><th>Idopont</th></tr>";
    foreach ($data['belepes'] as $row) {
        echo "<tr><td>{$row['nezoid']}</td><td>{$row['meccsid']}</td><td>{$row['idopont']}</td></tr>";
    }
    echo "</table>";
    echo "<h2 id='meccs-table'>Meccs tábla</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Dátum</th><th>Kezdés</th><th>Belépő</th><th>Típus</th></tr>";
    foreach ($data['meccs'] as $row) {
        echo "<tr><td>{$row['id']}</td><td>{$row['datum']}</td><td>{$row['kezdes']}</td><td>{$row['belepo']}</td><td>{$row['tipus']}</td></tr>";
    }
    echo "</table>";
    echo "<h2 id='nezo-table'>Nezo tábla</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Név</th><th>Férfi</th><th>Bérletes</th></tr>";
    foreach ($data['nezo'] as $row) {
        echo "<tr><td>{$row['id']}</td><td>{$row['nev']}</td><td>" . ($row['ferfi'] ? 'Igen' : 'Nem') . "</td><td>" . ($row['berletes'] ? 'Igen' : 'Nem') . "</td></tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "Hiba történt: " . $e->getMessage();
}
?>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<script src="assets/js/main.js"></script>
</body>
</html>
