<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Personas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<h1 class="fw-bold text-center m-5">Consulta el porcentaje de las personas sospechosas </h1>

<div class="container mb-5">
<!-- Formulario de búsqueda por UserId -->
<form class="row g-3 align-items-center" action="" method="post">
    <label for="id" class="form-label fw-medium fs-5">Buscar por ID:</label>
    <input type="text" name="UserId" id="id" class="form-control">
    <button type="submit"  class="btn btn-dark">Buscar</button>
</form>
</div>


<?php
require "conexion.php";

function verificarActividadSospechosa($velocidad, $ritmo, $frecuenciaCardiaca) {
    // Rangos considerados normales
    $rangoVelocidad = array('min' => 2.0, 'max' => 4.0); // Ejemplo: de 2 a 4 metros por segundo
    $rangoRitmo = array('min' => 4.0, 'max' => 7.0); // Ejemplo: de 4 a 7 minutos por kilómetro
    $rangoFrecuenciaCardiaca = array('min' => 60, 'max' => 180); // Ejemplo: de 60 a 180 latidos por minuto

    // Verificar si los valores están fuera de lo normales
    $sospechosa = false;
    if ($velocidad < $rangoVelocidad['min'] || $velocidad > $rangoVelocidad['max']) {
        $sospechosa = true;
    }
    if ($ritmo < $rangoRitmo['min'] || $ritmo > $rangoRitmo['max']) {
        $sospechosa = true;
    }
    if ($frecuenciaCardiaca < $rangoFrecuenciaCardiaca['min'] || $frecuenciaCardiaca > $rangoFrecuenciaCardiaca['max']) {
        $sospechosa = true;
    }

    return $sospechosa;
}


function mostrarInformacionPersona($conexion, $UserId) {
    // Search de personas sospechosas por su UserId
    $query = "SELECT * FROM swetrotest WHERE UserId = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $UserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    echo '<div class="container ">';
    echo '<h2 class="fw-bolder mb-2">Detalles del Usuario</h2>';
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>ID</th><th>Userid</th><th>Velocidad Máxima</th><th>Ritmo Promedio</th><th>Frecuencia Cardíaca</th><th>Sospechoso</th></tr></thead>';
    echo '<tbody>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $velocidad_maxima = $row['AverageSpeedInMetersPerSecond'];
            $ritmo_promedio = $row['AveragePaceInMinutesPerKilometer'];
            $frecuencia_cardiaca = $row['AverageHeartRateInBeatsPerMinute'];

            // Verificamos la Actividad sospechosas
            $es_sospechoso = verificarActividadSospechosa($velocidad_maxima, $ritmo_promedio, $frecuencia_cardiaca);

            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['UserId'] . '</td>';
            echo '<td>' . $velocidad_maxima . '</td>';
            echo '<td>' . $ritmo_promedio . '</td>';
            echo '<td>' . $frecuencia_cardiaca . '</td>';
        
            echo '<td>' . ($es_sospechoso ? 'Sí' : 'No') . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">No se encontraron resultados para el ID proporcionado.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}



function mostrarPersonasRelevantes($conexion) {
    // Vista previa de 10 usuarios 
    $query = "SELECT * FROM swetrotest LIMIT 10";
    $result = $conexion->query($query);
    echo '<div class="container mb-5">';
    echo '<h2 class="fw-bolder">Personas Relevantes</h2>';
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>ID</th><th>Userid</th><th>Velocidad Máxima</th><th>Ritmo Promedio</th><th>Frecuencia Cardíaca</th><th>Sospechoso</th></tr></thead>';
    echo '<tbody>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $velocidad_maxima = $row['AverageSpeedInMetersPerSecond'];
            $ritmo_promedio = $row['AveragePaceInMinutesPerKilometer'];
            $frecuencia_cardiaca = $row['AverageHeartRateInBeatsPerMinute'];

               // Vista previa de la Verificacion de Actividad sospechosas
            $es_sospechoso = verificarActividadSospechosa($velocidad_maxima, $ritmo_promedio, $frecuencia_cardiaca);

            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['UserId'] . '</td>';
            echo '<td>' . $velocidad_maxima . '</td>';
            echo '<td>' . $ritmo_promedio . '</td>';
            echo '<td>' . $frecuencia_cardiaca . '</td>';
            echo '<td>' . ($es_sospechoso ? 'Sí' : 'No') . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6">No se encontraron personas relevantes.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div">';
}
?>

<!-- Hacer null la alerta predeterminada por el navegador -->
   <script>
        history.replaceState(null,null,location.pathname)
    </script>


<!-- Script de bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
