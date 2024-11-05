<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        
        $telefone = $_GET['telefone'];
        $id_especialidade = $_GET['id_especialidade'];

        $result = $conn->query("call getAgenda('$telefone', $id_especialidade)");
        
        $agenda = "";
        while ($row = $result->fetch_assoc()) {
            $agenda =  $agenda.$row['id_temp']." - ".$row['horario_formatado'].PHP_EOL;
        }
        echo json_encode(['lista_horarios' => $agenda]);
        

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
