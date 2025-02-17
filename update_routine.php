<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$conn->begin_transaction();

try {
    // Atualiza a rotina principal
    $stmt = $conn->prepare("UPDATE rotinas SET nome = ?, data_inicio = ?, data_termino = ? WHERE id = ?");
    $stmt->bind_param("sssi", $_POST['nome'], $_POST['data_inicio'], $_POST['data_termino'], $_POST['id']);
    $stmt->execute();

    // Remove dias antigos
    $stmt = $conn->prepare("DELETE FROM rotina_dias WHERE rotina_id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();

    // Insere novos dias
    if (isset($_POST['dias'])) {
        $stmt = $conn->prepare("INSERT INTO rotina_dias (rotina_id, dia_semana) VALUES (?, ?)");
        foreach ($_POST['dias'] as $dia) {
            $stmt->bind_param("ii", $_POST['id'], $dia);
            $stmt->execute();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?> 