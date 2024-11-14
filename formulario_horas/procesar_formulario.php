<?php
// Establecer la zona horaria de Colombia
date_default_timezone_set('America/Bogota');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar la fecha y hora seleccionada
    $fechaSeleccionada = $_POST['fecha'];
    $horaSeleccionada = $_POST['atime'];

    // Obtener la fecha actual de acuerdo a la zona horaria de Colombia
    $fechaActual = date('Y-m-d');

    // Validar que la fecha seleccionada no sea anterior a la actual
    if ($fechaSeleccionada < $fechaActual) {
        echo "Error: No puedes seleccionar una fecha anterior a la actual.";
        exit;
    }

    // Mostrar la fecha y hora seleccionada si todo es correcto
    echo "La cita ha sido reservada para la fecha: " . $fechaSeleccionada . " a las " . $horaSeleccionada;
}
?>