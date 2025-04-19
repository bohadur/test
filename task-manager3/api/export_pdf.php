<?php
require '../config/database.php';
require '../vendor/autoload.php'; // mPDF

use Mpdf\Mpdf;

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.*, c.name as category
    FROM tasks t
    LEFT JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ?
    ORDER BY t.deadline, t.priority DESC
");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = "<h1>Список задач</h1><table border='1' cellspacing='0' cellpadding='4'><tr>
<th>Название</th><th>Описание</th><th>Категория</th><th>Приоритет</th><th>Срок</th><th>Статус</th></tr>";

foreach ($tasks as $t) {
    $html .= "<tr>
        <td>{$t['title']}</td>
        <td>{$t['description']}</td>
        <td>{$t['category']}</td>
        <td>{$t['priority']}</td>
        <td>{$t['deadline']}</td>
        <td>" . ($t['is_done'] ? '✅' : '❌') . "</td>
    </tr>";
}

$html .= "</table>";

$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("tasks.pdf", "D");
?>