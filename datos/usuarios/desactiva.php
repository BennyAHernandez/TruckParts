<?php
require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

$id = $_POST['id_usuario'];

// Llamar al procedimiento almacenado
$sql = $con->prepare("CALL sp_suspender_usuario_jesus(?, @p_resultado)");
$sql->execute([$id]);

// Obtener el resultado del procedimiento almacenado
$result = $con->query("SELECT @p_resultado AS resultado");
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row['resultado']) {
    header("Location: index.php");
} else {
    echo "Error al suspender el usuario.";
}


