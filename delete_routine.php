<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$conn->begin_transaction();

try {
    // Remove dias da rotina
    $stmt = $conn->prepare("DELETE FROM rotina_dias WHERE rotina_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Remove a rotina
    $stmt = $conn->prepare("DELETE FROM rotinas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?> 