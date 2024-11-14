<?php
session_start();

// Configuración de la base de datos
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '132197',
    'db' => 'barberia_db'
];

// Función para conectar a la base de datos
function connectDB($config)
{
    try {
        $conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
        if ($conn->connect_error) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

function obtenerBarberosConCalificacion($conn)
{
    $query = "
        SELECT b.nombre, COUNT(c.id) AS cantidad_reservas
        FROM barberos b
        LEFT JOIN citas c ON b.nombre = c.barbero AND c.estado != 'cancelada'
        GROUP BY b.nombre
        ORDER BY cantidad_reservas DESC
    ";

    $result = $conn->query($query);
    $barberos = [];
    $calificacion_maxima = 5; // Calificación máxima en estrellas

    // Obtener la cantidad de reservas del barbero más popular como referencia para la calificación
    $max_cantidad_reservas = $result->num_rows ? $result->fetch_assoc()['cantidad_reservas'] : 1;

    $result->data_seek(0); // Volver al primer registro

    while ($row = $result->fetch_assoc()) {
        // Calcular estrellas proporcionalmente
        $calificacion = round(($row['cantidad_reservas'] / $max_cantidad_reservas) * $calificacion_maxima);
        $barberos[] = ['nombre' => $row['nombre'], 'calificacion' => $calificacion];
    }

    return $barberos;
}

function obtenerCitasReservadas($conn, $fecha, $barbero)
{
    $query = "SELECT TIME(fecha) as hora, duracion_total 
              FROM citas 
              WHERE DATE(fecha) = ? 
              AND barbero = ?
              AND estado != 'cancelada'";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $fecha, $barbero);
    $stmt->execute();
    $result = $stmt->get_result();

    $horas_ocupadas = array();

    while ($row = $result->fetch_assoc()) {
        $hora_inicio = strtotime($fecha . ' ' . $row['hora']);
        $hora_fin = strtotime("+{$row['duracion_total']} minutes", $hora_inicio);

        // Marcar cada intervalo de 30 minutos dentro de la cita como ocupado
        for ($t = $hora_inicio; $t < $hora_fin; $t += 1800) { // 1800 segundos = 30 minutos
            $horas_ocupadas[] = date('H:i', $t);
        }
    }

    return $horas_ocupadas;
}


// Función para calcular duración total y precio total
function calcularTotales($conn, $servicios)
{
    $duracion_total = 0;
    $precio_total = 0;
    $servicios_array = explode(',', $servicios);

    foreach ($servicios_array as $servicio) {
        $stmt = $conn->prepare("SELECT duracion, precio FROM servicios WHERE nombre = ?");
        $stmt->bind_param("s", $servicio);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $duracion_total += $row['duracion'];
            $precio_total += $row['precio'];
        }
    }

    return ['duracion' => $duracion_total, 'precio' => $precio_total];
}

// Función para verificar disponibilidad considerando duración del servicio
function verificarDisponibilidad($conn, $barbero, $fecha, $hora, $duracion)
{
    $fecha_hora = $fecha . ' ' . $hora;
    $fecha_hora_fin = date('Y-m-d H:i:s', strtotime($fecha_hora . " + $duracion minutes"));

    $query = "SELECT COUNT(*) as count FROM citas 
              WHERE barbero = ? 
              AND ((fecha BETWEEN ? AND ?) 
              OR (DATE_ADD(fecha, INTERVAL duracion_total MINUTE) BETWEEN ? AND ?))
              AND estado != 'cancelada'";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $barbero, $fecha_hora, $fecha_hora_fin, $fecha_hora, $fecha_hora_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['count'] == 0;
}

// Conectar a la base de datos
$conn = connectDB($config);

if (isset($_POST['text'])) {
    $getMesg = mysqli_real_escape_string($conn, $_POST['text']);

    if (!isset($_SESSION['step'])) {
        $_SESSION['step'] = 0;
    }

    switch ($_SESSION['step']) {
        case 0:
            if (stripos($getMesg, 'reservar') !== false || stripos($getMesg, 'cita') !== false) {
                echo "¡Bienvenido a nuestra barbería! 😊 Por favor, indícame tu nombre.";
                $_SESSION['step'] = 1;
            } else {
                echo "¡Hola! Puedo ayudarte a reservar una cita. Solo escribe 'reservar cita' para comenzar.";
            }
            break;

        case 1:
            $_SESSION['nombre'] = $getMesg;
            echo "Gracias {$getMesg}. Por favor, indícame tu número de teléfono.";
            $_SESSION['step'] = 2;
            break;

        case 2:
            if (preg_match("/^[0-9]{8,15}$/", $getMesg)) {
                $_SESSION['telefono'] = $getMesg;
                echo "¿Qué fecha deseas reservar? Por favor selecciona una fecha.<br>";
                echo '<div class="date-picker-container">';
                echo '<input type="text" id="datePicker" class="form-control datepicker" readonly>';
                echo '</div>';
                $_SESSION['step'] = 3;
            } else {
                echo "Por favor, introduce un número de teléfono válido (8-15 dígitos).";
            }
            break;

        case 3:
            if (strtotime($getMesg) !== false) {
                $_SESSION['fecha'] = $getMesg;
                $_SESSION['servicios'] = array(); // Inicializar array de servicios

                echo "Selecciona los servicios que deseas (puedes elegir varios):<br><br>";

                $query = "SELECT nombre, precio, duracion FROM servicios ORDER BY nombre";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<button class='service-btn btn btn-outline-primary m-1' 
                          data-service='" . $row['nombre'] . "'
                          onclick='toggleService(\"" . $row['nombre'] . "\", " . $row['precio'] . ", " . $row['duracion'] . ")'>"
                        . $row['nombre'] . " - $" . $row['precio'] . "
                          <br><small>" . $row['duracion'] . " min</small></button>";
                }

                echo "<br><br><div id='selected-services' class='alert alert-info' style='display:none;'>
                      Servicios seleccionados: <span id='services-list'></span>
                      <br>Duración total: <span id='total-duration'>0</span> min
                      <br>Precio total: $<span id='total-price'>0</span>
                      </div>";

                echo "<br><button class='btn btn-success' onclick='confirmarServicios()'>Confirmar servicios</button>";
                $_SESSION['step'] = 4;
            } else {
                echo "Por favor, selecciona una fecha válida usando el calendario.";
            }
            break;

            // Modificar la parte del case 4 en el switch del archivo PHP
            // Modificar la parte de mostrar los botones de barberos en el case 4
        case 4:
            if ($getMesg === "CONFIRMAR_SERVICIOS") {
                if (!empty($_SESSION['servicios_temp'])) {
                    $_SESSION['servicios'] = $_SESSION['servicios_temp'];
                    $totales = calcularTotales($conn, implode(',', $_SESSION['servicios']));
                    $_SESSION['duracion_total'] = $totales['duracion'];
                    $_SESSION['precio_total'] = $totales['precio'];

                    // Crear mensaje personalizado para el usuario
                    $servicios_lista = implode(', ', $_SESSION['servicios']);
                    $respuesta = "Has seleccionado los siguientes servicios:\n";
                    $respuesta .= "- " . $servicios_lista . "\n";
                    $respuesta .= "Duración total: " . $totales['duracion'] . " minutos\n";
                    $respuesta .= "Precio total: $" . number_format($totales['precio'], 2) . "\n\n";
                    $respuesta .= "¿Con qué barbero deseas hacer la cita?<br><br>";

                    // Obtener barberos con calificación
                    $barberos = obtenerBarberosConCalificacion($conn);

                    // Mostrar los barberos con su calificación en estrellas
                    foreach ($barberos as $barbero) {
                        $calificacion = str_repeat("⭐", $barbero['calificacion']);
                        $respuesta .= "<button class='barber-btn btn btn-outline-secondary m-1' 
                                onclick='sendMessage(\"" . $barbero['nombre'] . "\")'>"
                            . $barbero['nombre'] . " - $calificacion" . "</button><br>";
                    }

                    echo $respuesta;
                    $_SESSION['step'] = 5;
                } else {
                    echo "Por favor, selecciona al menos un servicio.";
                    $_SESSION['step'] = 3;
                }
            } else {
                // Actualizar servicios silenciosamente
                $_SESSION['servicios_temp'] = explode(',', $getMesg);
                echo "_INTERNAL_UPDATE_"; // Señal interna que será filtrada por JavaScript
            }
            break;
        case 5:
            $_SESSION['barbero'] = $getMesg;
            echo "<div class='hora-selector'>";
            echo "<h5>Selecciona una hora:</h5>";
            echo "<div class='mb-3'>";
            echo "<span class='badge badge-info mr-2'>🕒 Disponible</span>";
            echo "<span class='badge badge-success'>✓ Reservado</span>";
            echo "</div>";

            $horas_ocupadas = obtenerCitasReservadas($conn, $_SESSION['fecha'], $_SESSION['barbero']);

            // Generar horas disponibles
            $horas_trabajo = range(9, 17); // 9 AM a 5 PM
            echo "<div class='time-buttons'>";
            foreach ($horas_trabajo as $hora) {
                for ($minutos = 0; $minutos < 60; $minutos += 30) {
                    $tiempo = sprintf("%02d:%02d", $hora, $minutos);

                    // Verificar si la hora está ocupada
                    $esta_ocupada = in_array($tiempo, $horas_ocupadas);

                    if ($esta_ocupada) {
                        echo "<button class='time-btn btn m-1 reserved' 
                                  style='background-color: #28a745; color: white; text-decoration: line-through; opacity: 0.7;'
                                  disabled>$tiempo</button>";
                    } else {
                        // Verificar si hay suficiente tiempo para el servicio solicitado
                        if (verificarDisponibilidad($conn, $_SESSION['barbero'], $_SESSION['fecha'], $tiempo, $_SESSION['duracion_total'])) {
                            echo "<button class='time-btn btn btn-outline-info m-1' 
                                      onclick='sendMessage(\"$tiempo\")'>$tiempo</button>";
                        }
                    }
                }
            }
            echo "</div></div>";
            $_SESSION['step'] = 6;
            break;
        case 6:
            $_SESSION['hora'] = $getMesg;

            if (verificarDisponibilidad($conn, $_SESSION['barbero'], $_SESSION['fecha'], $_SESSION['hora'], $_SESSION['duracion_total'])) {
                $stmt = $conn->prepare("INSERT INTO citas (cliente_nombre, telefono, servicio, barbero, fecha, duracion_total, precio_total, estado) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')");

                $nombre = $_SESSION['nombre'];
                $telefono = $_SESSION['telefono'];
                $servicios = implode(', ', $_SESSION['servicios']);
                $barbero = $_SESSION['barbero'];
                $fecha_hora = $_SESSION['fecha'] . ' ' . $_SESSION['hora'];
                $duracion_total = $_SESSION['duracion_total'];
                $precio_total = $_SESSION['precio_total'];

                $stmt->bind_param("sssssdd", $nombre, $telefono, $servicios, $barbero, $fecha_hora, $duracion_total, $precio_total);

                if ($stmt->execute()) {
                    echo "¡Perfecto! Tu cita ha sido reservada con éxito. 👍<br><br>";
                    echo "Resumen de tu cita:<br>";
                    echo "- Nombre: $nombre<br>";
                    echo "- Servicios: $servicios<br>";
                    echo "- Duración total: $duracion_total minutos<br>";
                    echo "- Precio total: $$precio_total<br>";
                    echo "- Barbero: $barbero<br>";
                    echo "- Fecha: $_SESSION[fecha]<br>";
                    echo "- Hora: $_SESSION[hora]<br><br>";
                    echo "Te enviaremos un recordatorio al número $telefono.<br>";
                    echo "¿Necesitas algo más? Escribe 'reservar cita' para hacer otra reserva.";
                } else {
                    echo "Lo siento, hubo un problema al reservar tu cita. Por favor, intenta nuevamente.";
                }
            } else {
                echo "Lo siento, esa hora ya no está disponible. Por favor, elige otra hora.";
                $_SESSION['step'] = 5;
                break;
            }

            session_destroy();
            break;

        default:
            session_destroy();
            echo "Ha ocurrido un error. Por favor, escribe 'reservar cita' para comenzar de nuevo.";
            break;
    }
}
