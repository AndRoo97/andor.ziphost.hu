<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restful Kliens</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
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
<body>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <h1>Restful Kliens</h1>
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
<div class="container mt-5">


    <h2>GET: Adatok lekérdezése</h2>
    <form id="get-form" class="mb-4">
        <div class="mb-3">
            <label for="get-id" class="form-label">Adat ID (opcionális):</label>
            <input type="text" id="get-id" class="form-control" placeholder="Hagyja üresen az összes adat lekérdezéséhez">
        </div>
        <button type="button" class="btn btn-primary" onclick="performGet()">Küldés</button>
    </form>
    <pre id="get-response" class="bg-light p-3 border rounded"></pre>

    <h2>POST: Új rekord létrehozása</h2>
    <form id="post-form" class="mb-4">
        <div class="mb-3">
            <label for="post-datum" class="form-label">Dátum:</label>
            <input type="date" id="post-datum" class="form-control">
        </div>
        <div class="mb-3">
            <label for="post-kezdes" class="form-label">Kezdés:</label>
            <input type="time" id="post-kezdes" class="form-control">
        </div>
        <div class="mb-3">
            <label for="post-belepo" class="form-label">Belépő:</label>
            <input type="number" id="post-belepo" class="form-control" value="1000">
        </div>
        <div class="mb-3">
            <label for="post-tipus" class="form-label">Típus:</label>
            <input type="text" id="post-tipus" class="form-control" value="kupa">
        </div>
        <button type="button" class="btn btn-primary" onclick="performPost()">Küldés</button>
    </form>
    <pre id="post-response" class="bg-light p-3 border rounded"></pre>

    <h2>PUT: Rekord módosítása</h2>
    <form id="put-form" class="mb-4">
        <div class="mb-3">
            <label for="put-id" class="form-label">Rekord ID:</label>
            <input type="number" id="put-id" class="form-control">
        </div>
        <div class="mb-3">
            <label for="put-datum" class="form-label">Új Dátum:</label>
            <input type="date" id="put-datum" class="form-control">
        </div>
        <div class="mb-3">
            <label for="put-kezdes" class="form-label">Új Kezdés:</label>
            <input type="time" id="put-kezdes" class="form-control" >
        </div>
        <div class="mb-3">
            <label for="put-belepo" class="form-label">Új Belépő:</label>
            <input type="number" id="put-belepo" class="form-control" value="1000">
        </div>
        <div class="mb-3">
            <label for="put-tipus" class="form-label">Új Típus:</label>
            <input type="text" id="put-tipus" class="form-control" value="döntö">
        </div>
        <button type="button" class="btn btn-primary" onclick="performPut()">Küldés</button>
    </form>
    <pre id="put-response" class="bg-light p-3 border rounded"></pre>

    <h2>DELETE: Rekord törlése</h2>
    <form id="delete-form" class="mb-4">
        <div class="mb-3">
            <label for="delete-id" class="form-label">Rekord ID:</label>
            <input type="number" id="delete-id" class="form-control">
        </div>
        <button type="button" class="btn btn-danger" onclick="performDelete()">Küldés</button>
    </form>
    <pre id="delete-response" class="bg-light p-3 border rounded"></pre>
</div>

<script>
    const apiUrl = 'http://andor.ziphost.hu/restful_server.php';

    function performGet() {
        const id = document.getElementById('get-id').value;
        fetch(`${apiUrl}${id ? `?id=${id}` : ''}`)
            .then(response => response.json())
            .then(data => document.getElementById('get-response').textContent = JSON.stringify(data, null, 2))
            .catch(error => document.getElementById('get-response').textContent = error);
    }

    function performPost() {
        const data = {
            datum: document.getElementById('post-datum').value,
            kezdes: document.getElementById('post-kezdes').value,
            belepo: document.getElementById('post-belepo').value,
            tipus: document.getElementById('post-tipus').value,
        };
        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => document.getElementById('post-response').textContent = JSON.stringify(data, null, 2))
            .catch(error => document.getElementById('post-response').textContent = error);
    }

    function performPut() {
        const id = document.getElementById('put-id').value;
        const data = {
            datum: document.getElementById('put-datum').value,
            kezdes: document.getElementById('put-kezdes').value,
            belepo: document.getElementById('put-belepo').value,
            tipus: document.getElementById('put-tipus').value,
        };
        fetch(`${apiUrl}?id=${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => document.getElementById('put-response').textContent = JSON.stringify(data, null, 2))
            .catch(error => document.getElementById('put-response').textContent = error);
    }

    function performDelete() {
        const id = document.getElementById('delete-id').value;
        fetch(`${apiUrl}?id=${id}`, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => document.getElementById('delete-response').textContent = JSON.stringify(data, null, 2))
            .catch(error => document.getElementById('delete-response').textContent = error);
    }
</script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<script src="assets/js/main.js"></script>
</body>
</html>
