<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':    

       
        if (isset($_GET['id_especialidade'])) 
        {
            $id_especialidade = $_GET['id_especialidade'];
            $id_temp = $_GET['id_temp'];
            $telefone = $_GET['telefone'];

            $result = $conn->query("SELECT horario_formatado, id_temp, id_agenda
                                        from agenda_temp
                                        where id_especialidade = $id_especialidade
                                        and id_temp = $id_temp
                                        and telefone = '$telefone'");
            
            $data = $result->fetch_assoc();
            echo json_encode(value : $data);
                             
        }
        else {
            $telefone = $_GET['telefone'];
            $result = $conn->query("select a.horario_formatado, p.telefone, e.descricao from agenda a
                                            inner join paciente p on a.id_paciente = p.id_paciente
                                            inner join especialidade e on e.id_especialidade = a.id_especialidade 
                                            and p.telefone = '$telefone'");
            $consultas = "";
            while ($row = $result->fetch_assoc()) {
                $consultas = $consultas.$row["descricao"]." ".$row["horario_formatado"].PHP_EOL;
            }
            echo json_encode(['consultas' => $consultas]);
        }
            

        break;

    case 'POST':
        $id_agenda = $input['id_agenda'];
        $id_paciente = $input['id_paciente'];
        
        $conn->query("call updateAgenda($id_agenda, $id_paciente, @affected_rows)");
        $result = $conn->query("SELECT @affected_rows AS affected_rows");
        
        if ($result) {
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Erro ao buscar número de linhas afetadas"]);
        }
        
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
