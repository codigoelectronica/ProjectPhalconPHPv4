<?php
$usuario = "root";
$contraseña = "pass";

try {
    $mbd = new PDO('mysql:host=ProjectPhalconPHP_mysql;dbname=docker', $usuario, $contraseña);
    echo "<pre>";
    foreach($mbd->query('SELECT * from test') as $fila) {
        print_r($fila);
    }
    echo "</pre>";
    $mbd = null;
} catch (PDOException $e) {
    print_r($e->getMessage());
    die();
}
?>