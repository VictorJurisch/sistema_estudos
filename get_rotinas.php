<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_estudos";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Busca as rotinas no banco de dados
$sql = "SELECT nome, data_inicio, data_termino FROM rotinas";
$result = $conn->query($sql);

$rotinas = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rotinas[] = $row;
    }
}

$conn->close();

// Retorna as rotinas em formato JSON
header('Content-Type: application/json');
echo json_encode($rotinas);
?>