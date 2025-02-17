<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$id = $_GET['id'];
$response = array();

$sql = "SELECT r.*, GROUP_CONCAT(rd.dia_semana) as dias 
        FROM rotinas r 
        LEFT JOIN rotina_dias rd ON r.id = rd.rotina_id 
        WHERE r.id = ?
        GROUP BY r.id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $row['dias'] = $row['dias'] ? explode(',', $row['dias']) : [];
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Rotina não encontrada']);
}

$conn->close();
?>
