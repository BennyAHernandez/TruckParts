<?php
require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}


//conexión a la base de datos
$db = new Database();
$con = $db->conectar();

//realizamos la consulta a la tabla categoria y almacenamos los datos en una variable llamada $resultado para proyectarlos en un table

$sql = "SELECT id_pieza, nombre_pieza, marca, codigo, descripcion, precio, cantidad_disponible, id_cat, spin FROM
 productos WHERE activo = 1";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);



?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Productos</h2>

        <a href="nuevo.php" class="btn btn-primary">Nuevo</a>

        <hr>

        <div
            class="table-responsive">
            <table
                class="table table-hover">
                <thead>
                    <tr>
                        <th>NOMBRE</th>
                        <th>MARCA</th>
                        <th>CÓDIGO</th>
                        <th>PRECIO</th>
                        <th>CANTIDAD DISPONIBLE</th>
                        <th></th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $producto){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre_pieza'], ENT_QUOTES); ?></td>
                            <td><?php echo $producto['marca']; ?></td>
                            <td><?php echo $producto['codigo']; ?></td>
                            <td><?php echo $producto['precio']; ?></td>
                            <td><?php echo $producto['cantidad_disponible']; ?></td>
                            <td>
                                <a href="editar.php?id_pieza=<?php echo $producto['id_pieza']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalElimina"
                                    data-bs-id="<?php echo $producto['id_pieza']; ?>">
                                    Eliminar
                                </button>

                            </td>


                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


    </div>
</main>



<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div
    class="modal fade"
    id="modalElimina"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"

    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div
        class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Eliminar
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">¿Desea eliminar esta categoría?</div>
            <div class="modal-footer">
                <form action="elimina.php" method="post">
                    <input type="hidden" name="id_pieza">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
<script>
    let eliminaModal = document.getElementById('modalElimina')
    eliminaModal.addEventListener('show.bs.modal', function(event){
        let button = event.relatedTarget
        let id_cat = button.getAttribute('data-bs-id')

        let modalInput = eliminaModal.querySelector('.modal-footer input')
        modalInput.value = id_cat
    })
</script>



<?php require '../footer.php'; ?>