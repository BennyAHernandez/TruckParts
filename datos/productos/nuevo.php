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


//consulta a la bd de categorias para habilitar el botón de categorias en el formulario
$sql = "SELECT id_cat, nombre FROM categoria WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    .ck-editor__editable[role="textbox"] {
        min-height: 250px;
    }
</style>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.css" />

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Nuevo producto</h2>

        <form action="guarda.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="nombre_pieza" class="form-label">Nombre</label>
                <input
                    type="text"
                    class="form-control"
                    name="nombre_pieza"
                    id="nombre_pieza"
                    required
                    autofocus />
            </div>
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input
                    type="text"
                    class="form-control"
                    name="marca"
                    id="marca"
                    required />
            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label">Codigo</label>
                <input
                    type="text"
                    class="form-control"
                    name="codigo"
                    id="codigo"
                    required />
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea
                    class="form-control"
                    name="descripcion"
                    id="editor"
                    >
                </textarea>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input
                        type="number"
                        class="form-control"
                        name="precio"
                        id="precio"
                        required />
                </div>

                <div class="col mb-3">
                    <label for="cantidad_disponible" class="form-label">Cantidad Disponible</label>
                    <input
                        type="number"
                        class="form-control"
                        name="cantidad_disponible"
                        id="cantidad_disponible"
                        required />
                </div>

                <div class="mb-3">
                <label for="spin" class="form-label">Link video 3D</label>
                <input
                    type="text"
                    class="form-control"
                    name="spin"
                    id="spin"
                     />
            </div>

            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="imagen_principal" class="form-label">Imagen Principal</label>
                    <input
                        type="file"
                        class="form-control"
                        name="imagen_principal"
                        id="imagen_principal"
                        accept="image/jpeg"
                        required
                         />
                </div>

                <div class="col"> 
                    <label for="otras_imagenes" class="form-label">Otras_imagenes</label>
                    <input
                        type="file"
                        class="form-control"
                        name="otras_imagenes[]"
                        id="otras_imagenes"
                        accept="image/jpeg"
                        multiple
                         />
                </div>
            </div>

            

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="id_cat" class="form-label">Categoría</label>
                    <select
                        class="form-select"
                        name="id_cat"
                        id="id_cat" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id_cat']; ?>"><?php echo $categoria['nombre']; ?></option>
                        <?php } ?>
                    </select>

                </div>
            </div>


            <button
                type="submit"
                class="btn btn-primary">Guardar
            </button>


        </form>

    </div>
</main>

<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.0/"
        }
    }
</script>

<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph
    } from 'ckeditor5';

    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        } )
        .then( /* ... */ )
        .catch( /* ... */ );
</script>

<?php require '../footer.php'; ?>