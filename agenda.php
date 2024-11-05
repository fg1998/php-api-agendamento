<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        
        $especialidade = $_GET['especialidade'];

        // Define o locale para português do Brasil
        //$formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
        //$formatter->setPattern('E dd/MM HH:mm'); // Formato para 'Seg 01/11 09:00'
        
        $result = $conn->query("SELECT a.id_agenda, a.horario, e.descricao, a.horario_formatado
                                        FROM agenda a 
                                        INNER JOIN especialidade e ON e.id_especialidade = a.id_especialidade 
                                        WHERE e.id_especialidade = $especialidade and id_paciente is null
                                        order by horario ");
        
        $agenda = [];
        $retorno = "";
        $linha = 1;
        while ($row = $result->fetch_assoc()) {
            // Converte e formata o campo 'horario' usando IntlDateFormatter
            //$timestamp = strtotime($row['horario']);
            //$row['horario'] = $formatter->format($timestamp);
        
            // Adiciona o dado com 'horario' formatado ao array
            $agenda[] = $row;
            $retorno = $retorno.$linha." - ".$row['horario_formatado'].PHP_EOL;
            $linha++;
        }
        
        echo json_encode(['agenda' => $retorno]);
        

        break;

    case 'POST':
        $horario = $input['horario'];
        $especialidade_selecionada = $input['especialidade_selecionada'];
        $id_paciente = $input['id_paciente'];
        
        $conn->query("update agenda a inner join especialidade e on a.id_especialidade = e.id_especialidade 
                            set id_paciente=$id_paciente
                            where a.horario_formatado='$horario' and e.descricao ='$especialidade_selecionada' ");
        echo json_encode(["message" => "Consulta agendanda com sucesso !!"]);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $name = $input['name'];
        $email = $input['email'];
        $age = $input['age'];
        $conn->query("UPDATE users SET name='$name',
                     email='$email', age=$age WHERE id=$id");
        echo json_encode(["message" => "User updated successfully"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $conn->query("DELETE FROM users WHERE id=$id");
        echo json_encode(["message" => "User deleted successfully"]);
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}

$conn->close();
?>
