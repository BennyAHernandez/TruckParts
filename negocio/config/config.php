<?php 

$path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

require_once $path .'database.php';
require_once $path . '../../datos/clases/cifrado.php';


//conexión a la base de datos
$db = new Database();
$con = $db->conectar();


//consulta a la tabla de configuracion para extraer sus datos
$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datosConfig = $resultado->fetchAll(PDO::FETCH_ASSOC);

//agragarlos a un arreglo

$config = [];

foreach($datosConfig as $datoConfig){
    $config[$datoConfig['nombre']] = $datoConfig['valor'];
}

define("SITE_URL", "http://localhost/truckpartsmx");
define("KEY_TOKEN", "DIE.uyd-2000");
define("MONEDA", "$");

//Config paypal
define("CLIENT_ID", "AU0UsuRnEyI0OWOFQ3wTZCXsBA3mJ88j1NwludDY6E6gxjHDhxikJMNizvKuYShRq7V6G20TlPSyaYHO");
define("CURRENCY", "MXN");

//config general para el envio de correos
define("MAIL_HOST", $config['correo_smtp']);
define("MAIL_USER", $config['correo_email']);
define("MAIL_PASS", descifrar($config['correo_password']));
define("MAIL_PORT", $config['correo_puerto']);

//config de sesion activa
session_name('user_session');
session_start();

//config predeterminada del carrito de compras en catalogo.php
$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>