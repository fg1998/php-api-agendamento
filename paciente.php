<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['telefone'])) {
            $telefone = $_GET['telefone'];
            $result = $conn->query("SELECT * FROM paciente WHERE telefone='$telefone'");
            $data = $result->fetch_assoc();
            echo json_encode($data); 
        } else {
            $result = $conn->query("SELECT * FROM paciente");
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        }
        break;

    case 'POST':
        $cpf = $input['cpf'];
        $nome = $input['nome'];
        $telefone = $input['telefone'];

        $cpfLimpo = preg_replace('/\D/', '', $cpf);

        $conn->query("INSERT INTO paciente (nome, telefone, cpf) VALUES ('$nome', '$telefone', '$cpfLimpo')");
        echo json_encode(["message" => "Cadastro realizado com sucesso !"]);
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
