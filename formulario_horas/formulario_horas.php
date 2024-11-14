<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Hora</title>
    <!-- Incluye jQuery Timepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
</head>
<body>

    <!-- Formulario PHP con campo de selección de hora -->
    <form action="procesar_formulario.php" method="post">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="fecha">Seleccionar Fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
            <label for="atime">Seleccionar Hora:</label>
            <input type="text" class="form-control appointment_time ui-timepicker-input" placeholder="Hora" name="atime" id="atime" required autocomplete="off">
        </div>
    </div>
    <button type="submit">Enviar</button>
</form>

    <!-- Incluye jQuery y jQuery Timepicker JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#atime').timepicker({
                timeFormat: 'h:mm p',
                interval: 30,
                minTime: '10:00am',
                maxTime: '08:00pm',
                defaultTime: '10:00am',
                startTime: '10:00am',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
    </script>

</body>
</html>