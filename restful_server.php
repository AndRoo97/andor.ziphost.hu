<?php
require_once 'config.php';

header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];
$table = "meccs";
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM $table");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        if ($input) {
            $columns = implode(", ", array_keys($input));
            $placeholders = implode(", ", array_fill(0, count($input), "?"));
            $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
            if ($stmt->execute(array_values($input))) {
                echo json_encode(["id" => $pdo->lastInsertId(), "message" => "Record created successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to create record"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($_GET['id']) && $input) {
            $columns = implode(" = ?, ", array_keys($input)) . " = ?";
            $stmt = $pdo->prepare("UPDATE $table SET $columns WHERE id = ?");
            $params = array_values($input);
            $params[] = $_GET['id'];

            if ($stmt->execute($params)) {
                echo json_encode(["message" => "Record updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to update record", "error" => $stmt->errorInfo()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request", "input" => $input, "id" => $_GET['id'] ?? null]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
            if ($stmt->execute([$_GET['id']])) {
                echo json_encode(["message" => "Record deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to delete record"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
