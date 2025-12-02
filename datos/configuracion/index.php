<?php 
require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../clases/cifrado.php';

if(!isset($_SESSION['user_type'])){
    header('Location: ../index.php');
    exit;
}

if($_SESSION['user_type'] != 'admin'){
    header('Location: ../index.php');
    exit;
}


//conexión a la base de datos
$db = new Database();
$con = $db->conectar();


//consulta a la tabla de configuracion para extraer sus datos
$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

//agragarlos a un arreglo

$config = [];

foreach($datos as $dato){
    $config[$dato['nombre']] = $dato['valor'];
}

?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Administración</h1>


    <form action="guarda.php" method="post">
        <div class="row">
            <div class="col-6">
                <label for="smtp">SMTP</label>
                <input class="form-control" type="text" name="smtp" id="smtp" value="<?php echo $config
                ['correo_smtp']; ?>">
            </div>

            <div class="col-6">
                <label for="puerto">Puerto</label>
                <input class="form-control" type="text" name="puerto" id="puerto" value="<?php echo $config
                ['correo_puerto']; ?>">
            </div>

            <div class="col-6">
                <label for="correo">Correo electrónico</label>
                <input class="form-control" type="email" name="correo" id="correo" value="<?php echo $config
                ['correo_email']; ?>">
            </div>

            <div class="col-6">
                <label for="contraseña">Contraseña</label>
                <input class="form-control" type="password" name="password" id="password" value="<?php echo $config
                ['correo_password']; ?>">
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </form>

    </div>
</main>


<?php require '../footer.php'; ?>