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

// Obtém os dados do formulário
$nome = $_POST['nome'];
$dias_da_semana = implode(", ", $_POST['dias_da_semana']);
$data_inicio = $_POST['data_inicio'];
$data_termino = $_POST['data_termino'];

// Insere os dados no banco de dados
$sql = "INSERT INTO rotinas (nome, dias_da_semana, data_inicio, data_termino) VALUES ('$nome', '$dias_da_semana', '$data_inicio', '$data_termino')";

if ($conn->query($sql) === TRUE) {
    echo "Nova rotina criada com sucesso";
    header("Location: rotinas.php");
    exit();
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>