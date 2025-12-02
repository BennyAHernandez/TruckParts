<?php 
require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Obtener el ID del producto a desactivar
$id = $_POST['id_pieza'];

// Llamar al Stored Procedure
$sql = $con->prepare("CALL sp_mod_del_jesus(?)");
$sql->execute([$id]);

// Redirigir al usuario después de la operación
header('Location: index.php');
?>
