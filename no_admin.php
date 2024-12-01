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
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            text-align: center;
        }
        .message-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Hozzáférés megtagadva</h1>
        <p class="text-danger">Nem rendelkezik adminisztrátori jogosultsággal.</p>
        <p>Csak adminisztrátorok férhetnek hozzá ehhez az oldalhoz.</p>
        <a href="index.php" class="btn btn-primary">Vissza a főoldalra</a>
    </div>
</body>
</html>
