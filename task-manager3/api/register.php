<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Введите имя и пароль"]);
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
try {
    $stmt->execute([$username, $hash]);
    echo json_encode(["message" => "Регистрация успешна"]);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(["error" => "Пользователь уже существует"]);
}
?>