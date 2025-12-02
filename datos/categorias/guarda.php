<?php 
require '../config/database.php';
require '../config/config.php';

if(!isset($_SESSION['user_type'])){
    header('Location: ../index.php');
    exit;
}

if($_SESSION['user_type'] != 'admin'){
    header('Location: ../../index.php');
    exit;
}

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

$nombre = $_POST['nombre'];

// Llamar al Stored Procedure para insertar la categoría
$sql = $con->prepare("CALL sp_ins_cat_jesus(?)");
$sql->execute([$nombre]);

header('Location: index.php');
?>
