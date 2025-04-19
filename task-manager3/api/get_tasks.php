<?php
require '../config/database.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Неавторизован"]);
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT t.*, c.name as category 
    FROM tasks t 
    LEFT JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ?
    ORDER BY t.deadline, t.priority DESC
");
$stmt->execute([$user_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>