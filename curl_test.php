<?php
echo "<h1>RESTful API Tesztelése</h1>";
echo "<a href='index.php'>Főoldal</a>";

$baseUrl = "http://andor.ziphost.hu/restful_server.php";

echo "<h2>GET: Összes rekord</h2>";
$ch = curl_init($baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "<pre>GET Response:\n" . json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

echo "<h2>POST: Új rekord létrehozása</h2>";
$data = [
    'datum' => '2023-12-01',
    'kezdes' => '18:00',
    'belepo' => 1200,
    'tipus' => 'bajnoki'
];
$ch = curl_init($baseUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$responseData = json_decode($response, true);
$newId = $responseData['id'] ?? null;
echo "<pre>POST Response:\n" . json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

if ($newId) {
    echo "<h2>PUT: Létező rekord módosítása</h2>";
    $updateData = [
        'datum' => '2023-12-10',
        'kezdes' => '20:00',
        'belepo' => 2500,
        'tipus' => 'döntő'
    ];
    $ch = curl_init("$baseUrl?id=$newId");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    echo "<pre>PUT Response:\n" . json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
}

if ($newId) {
    echo "<h2>DELETE: Rekord törlése</h2>";
    $ch = curl_init("$baseUrl?id=$newId");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    echo "<pre>DELETE Response:\n" . json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
}
