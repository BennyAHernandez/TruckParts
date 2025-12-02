<?php 
require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Recibimos el ID que se va a editar de las categorías por método GET
$id = $_GET['id_cat'];

// Llamar al Stored Procedure
$sql = $con->prepare("CALL sp_sel_cat_jesus(?)");
$sql->execute([$id]);
$categoria = $sql->fetch(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Editar categoria</h2>

        <form action="actualiza.php" method="post" autocomplete="off">
            <input type="hidden" name="id_cat" value="<?php echo $categoria['id_cat'] ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input
                    type="text"
                    class="form-control"
                    name="nombre"
                    id="nombre"
                    value="<?php echo $categoria['nombre'] ?>"
                    required
                    autofocus

                />
            </div>
            
            <button
                type="submit"
                class="btn btn-primary"
            >Guardar
            </button>
            

        </form>

    </div>
</main>


<?php require '../footer.php'; ?>