<?php
require '../config/database.php';
require '../vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Задачи");

$sheet->fromArray(["Название", "Описание", "Категория", "Приоритет", "Срок", "Статус"], NULL, 'A1');

$row = 2;
foreach ($tasks as $task) {
    $sheet->setCellValue("A{$row}", $task['title']);
    $sheet->setCellValue("B{$row}", $task['description']);
    $sheet->setCellValue("C{$row}", $task['category']);
    $sheet->setCellValue("D{$row}", $task['priority']);
    $sheet->setCellValue("E{$row}", $task['deadline']);
    $sheet->setCellValue("F{$row}", $task['is_done'] ? '✅ Выполнено' : '❌ Нет');
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="tasks.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>