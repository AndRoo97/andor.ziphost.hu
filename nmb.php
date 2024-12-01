<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NMB API</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
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
    <h1 class="mb-4" style="text-align: center;">Magyar Nemzeti Bank - Árfolyamok</h1>

    <?php
    class MNBSoapClient
    {
        private $client;

        public function __construct()
        {
            $this->initClient();
        }

        private function initClient(): void
        {
            $params = array(
                'soap_version'   => SOAP_1_2
            );
            try {
                $this->client = new SoapClient("https://www.mnb.hu/arfolyamok.asmx?wsdl", $params);
            } catch (SoapFault $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        public function getCurrencies(): array
        {
            $currenciesResult = $this->client->GetCurrencies();
            $element = new SimpleXMLElement(html_entity_decode($currenciesResult->GetCurrenciesResult));

            $currencies = [];
            foreach ($element->Currencies->Curr as $c) {
                $currencies[] = (string)$c;
            }
            return $currencies;
        }

        public function getExchangeRates(?string $fromDate, ?string $toDate, array $currencyNames): array
        {
            $params = [
                'startDate' => $fromDate,
                'endDate' => $toDate,
                'currencyNames' => implode(',', array_map('strtoupper', $currencyNames))
            ];

            $exchangeRatesResult = $this->client->GetExchangeRates($params);
            $element = new SimpleXMLElement(html_entity_decode($exchangeRatesResult->GetExchangeRatesResult));

            $rates = [];
            foreach ($element->Day as $day) {
                $date = (string)$day['date'];
                foreach ($day->Rate as $rate) {
                    $rates[] = [
                        'date' => $date,
                        'currency' => (string)$rate['curr'],
                        'unit' => (int)$rate['unit'],
                        'value' => (float)$rate
                    ];
                }
            }
            return $rates;
        }
    }

    try {
        $mnbClient = new MNBSoapClient();
        $currencies = $mnbClient->getCurrencies();
        $fromDate = $_GET['fromDate'] ?? date('Y-m-01');
        $toDate = $_GET['toDate'] ?? date('Y-m-d');
        $selectedCurrencies = isset($_GET['currency']) ? [$_GET['currency']] : ['EUR'];
        $rates = $mnbClient->getExchangeRates($fromDate, $toDate, $selectedCurrencies);
    } catch (Exception $e) {
        echo "<p class='text-danger'>Hiba történt: " . $e->getMessage() . "</p>";
        $rates = [];
    }
    ?>

    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="currency" class="form-label">Deviza</label>
                <select id="currency" name="currency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                        <option value="<?= htmlspecialchars($currency) ?>" <?= (isset($_GET['currency']) && $_GET['currency'] === $currency) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($currency) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="fromDate" class="form-label">Kezdő dátum</label>
                <input type="date" id="fromDate" name="fromDate" class="form-control" value="<?= htmlspecialchars($fromDate) ?>">
            </div>
            <div class="col-md-3">
                <label for="toDate" class="form-label">Végdátum</label>
                <input type="date" id="toDate" name="toDate" class="form-control" value="<?= htmlspecialchars($toDate) ?>">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Keresés</button>
            </div>
        </div>
    </form>

    <?php if (!empty($rates)): ?>
        <h2 style="text-align: center;">Árfolyamok (<?= htmlspecialchars($fromDate) ?> - <?= htmlspecialchars($toDate) ?>):</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Deviza</th>
                    <th>Egység</th>
                    <th>Árfolyam</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rates as $rate): ?>
                    <tr>
                        <td><?= htmlspecialchars($rate['date']) ?></td>
                        <td><?= htmlspecialchars($rate['currency']) ?></td>
                        <td><?= htmlspecialchars($rate['unit']) ?></td>
                        <td><?= htmlspecialchars($rate['value']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 style="text-align: center;">Grafikon</h2>
        <canvas id="exchangeRateChart"></canvas>

        <script>
            const labels = <?= json_encode(array_column($rates, 'date')) ?>;
            const data = <?= json_encode(array_column($rates, 'value')) ?>;

            const ctx = document.getElementById('exchangeRateChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Árfolyam',
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Dátum'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Árfolyam'
                            }
                        }
                    }
                }
            });
        </script>
    <?php else: ?>
        <p class="text-warning">Nincs elérhető adat a megadott paraméterekkel.</p>
    <?php endif; ?>
</div>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
