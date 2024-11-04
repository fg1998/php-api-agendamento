<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        try {
            $result = $conn->query("select * from medico");
            $especialidades = [];
            while ($row = $result->fetch_assoc()) {
                $especialidades[] = $row;
            }
            echo json_encode($especialidades);
        } catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    //        echo json_encode(['medico' => $especialidades]);
        

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
