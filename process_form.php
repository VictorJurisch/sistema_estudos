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
$data_inicio = $_POST['data_inicio'];
$data_termino = $_POST['data_termino'];
$dias = isset($_POST['dias']) ? $_POST['dias'] : [];

// Inicia a transação
$conn->begin_transaction();

try {
    // Prepara e executa a query para inserir a rotina
    $sql = "INSERT INTO rotinas (nome, data_inicio, data_termino) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $data_inicio, $data_termino);
    
    if ($stmt->execute()) {
        $rotina_id = $stmt->insert_id;
        
        // Prepara a query para inserir os dias
        $stmt_dias = $conn->prepare("INSERT INTO rotina_dias (rotina_id, dia_semana) VALUES (?, ?)");
        
        // Insere cada dia selecionado
        foreach ($dias as $dia) {
            $stmt_dias->bind_param("ii", $rotina_id, $dia);
            $stmt_dias->execute();
        }
        
        // Commit da transação
        $conn->commit();
        // Redirecionando com mensagem de sucesso
        header("Location: rotinas.php?status=success&message=Rotina salva com sucesso!");
        exit();
    } else {
        throw new Exception("Erro ao salvar a rotina");
    }
} catch (Exception $e) {
    // Em caso de erro, faz rollback da transação
    $conn->rollback();
    header("Location: rotinas.php?status=error&message=" . urlencode($e->getMessage()));
    exit();
}

$stmt->close();
$conn->close();
?>