<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_estudos";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexão falhou: " . $conn->connect_error]));
}

// Adiciona log para debug
error_log("Conectado ao banco de dados");

// Busca todas as rotinas com seus dias
$sql = "SELECT r.id, r.nome, r.data_inicio, r.data_termino, rd.dia_semana 
        FROM rotinas r 
        LEFT JOIN rotina_dias rd ON r.id = rd.rotina_id";

$result = $conn->query($sql);

// Log do número de resultados
error_log("Número de resultados: " . $result->num_rows);

$eventos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Log de cada linha encontrada
        error_log("Processando rotina: " . json_encode($row));
        
        $inicio = new DateTime($row['data_inicio']);
        $fim = new DateTime($row['data_termino']);
        $dia_semana = $row['dia_semana'];
        
        // Itera sobre o período da rotina
        $periodo = new DatePeriod(
            $inicio,
            new DateInterval('P1D'),
            $fim->modify('+1 day')
        );
        
        foreach ($periodo as $data) {
            if ($data->format('w') == $dia_semana) {
                $evento = array(
                    'id' => $row['id'],
                    'title' => $row['nome'],
                    'start' => $data->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => '#ff69b4',
                    'borderColor' => '#ff69b4',
                    'textColor' => '#ffffff'
                );
                $eventos[] = $evento;
                
                // Log de cada evento criado
                error_log("Criado evento: " . json_encode($evento));
            }
        }
    }
}

// Log do array final de eventos
error_log("Total de eventos gerados: " . count($eventos));
error_log("Eventos: " . json_encode($eventos));

echo json_encode($eventos);

$conn->close();
?>