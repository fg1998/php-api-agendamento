<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if(isset($_GET['especialidade'])) {
            $especialidade = $_GET['especialidade'];
            $result = $conn->query("select * from especialidade where id_especialidade=$especialidade");
            $data = $result->fetch_assoc();
            echo json_encode(value :['especialidade' => $data]);
        }
        else {

            $result = $conn->query("select * from especialidade ");
            $especialidades = [];
            $retorno = "";
            while ($row = $result->fetch_assoc()) {
                $especialidades[] = $row;
                $retorno = $retorno.$row["id_especialidade"]." - ".$row["descricao"].PHP_EOL;
            }
            //echo $retorno;
            echo json_encode(['especialidade' => $retorno]);
        }
        break;

    case 'POST':
        $name = $input['name'];
        $email = $input['email'];
        $age = $input['age'];
        $conn->query("INSERT INTO users (name, email, age) VALUES ('$name', '$email', $age)");
        echo json_encode(["message" => "User added successfully"]);
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
