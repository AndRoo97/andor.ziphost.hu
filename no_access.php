<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hozzáférés megtagadva</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Hozzáférés megtagadva</h1>
    <p>Az oldal megtekintéséhez regisztráció és bejelentkezés szükséges.</p>
    <a href="auth/register.php" class="btn btn-primary">Regisztráció</a>
    <a href="auth/login.php" class="btn btn-secondary">Bejelentkezés</a>
</div>
</body>
</html>
