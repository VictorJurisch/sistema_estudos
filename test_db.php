<?php
include 'db_connection.php';

// Testa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Testa a criação de uma task
$sql = "INSERT INTO kanban (name, column_name, position) VALUES ('Task Teste', 'To Do', 0)";
if ($conn->query($sql) === TRUE) {
    echo "Task criada com sucesso. ID: " . $conn->insert_id;
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

// Testa a leitura
$sql = "SELECT * FROM kanban";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br>id: " . $row["id"]. " - Name: " . $row["name"];
    }
} else {
    echo "<br>0 resultados";
}

$conn->close();
?> 