<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        
        $telefone = $_GET['telefone'];

        $result = $conn->query("select a.horario, e.descricao from agenda a 
                                        inner join paciente p on a.id_paciente = p.id_paciente 
                                        inner join especialidade e on a.id_especialidade = e.id_especialidade  
                                        where p.telefone ='$telefone'");
        
        $agenda = [];
        while ($row = $result->fetch_assoc()) {
            $agenda[] = $row;
        }
        echo json_encode(['agenda' => $agenda]);
        

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
