<?php 




$conexion = new mysqli("localhost", "root", "kimetsu", "test");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


    if (isset($_POST["UserId"])) {
        $UserId = $_POST["UserId"];
        mostrarInformacionPersona($conexion, $UserId);
    } else {
        mostrarPersonasRelevantes($conexion);
    }


$conexion->close();
?>