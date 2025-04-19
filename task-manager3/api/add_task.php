<?php
require '../config/database.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Неавторизован"]);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$stmt = $pdo->prepare("
    INSERT INTO tasks (user_id, title, description, category_id, priority, deadline)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $_SESSION['user_id'],
    $data['title'],
    $data['description'],
    $data['category_id'],
    $data['priority'],
    $data['deadline']
]);
echo json_encode(["message" => "Задача добавлена"]);
?>