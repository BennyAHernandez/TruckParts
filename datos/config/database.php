<?php


class Database {
    private $hostname = "localhost";
    private $database = "tienda_online";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    function conectar(){
        try{
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . ";
            charset=" . $this->charset;
            $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => FALSE
        ];
        $pdo = new PDO($conexion, $this->username, $this->password, $options);

        return $pdo;
        }catch(PDOException $e){
            echo 'Error de conexion: ' . $e->getMessage();
    }
}
}

/* DELIMITER //
CREATE PROCEDURE sp_ins_prod_jesus(
    IN p_nombre_pieza VARCHAR(255),
    IN p_marca VARCHAR(255),
    IN p_codigo VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_cantidad_disponible INT,
    IN p_id_cat INT,
    IN p_spin VARCHAR(50),
    OUT p_id INT
)
BEGIN
    INSERT INTO productos (nombre_pieza, marca, codigo, descripcion, precio, cantidad_disponible, id_cat, spin, activo)
    VALUES (p_nombre_pieza, p_marca, p_codigo, p_descripcion, p_precio, p_cantidad_disponible, p_id_cat, p_spin, 1);
    
    SET p_id = LAST_INSERT_ID();
END //
DELIMITER ; */
