<?php
include('includes/dbconnection.php');
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $services = $_POST['services'];
    $adate = $_POST['adate'];
    $atime = $_POST['atime'];
    $phone = $_POST['phone'];
    $aptnumber = mt_rand(100000000, 999999999);

    $query = mysqli_query($con, "insert into tblappointment(AptNumber,Name,Email,PhoneNumber,AptDate,AptTime,Services) value('$aptnumber','$name','$email','$phone','$adate','$atime','$services')");
    if ($query) {
        $ret = mysqli_query($con, "select AptNumber from tblappointment where Email='$email' and  PhoneNumber='$phone'");
        $result = mysqli_fetch_array($ret);
        $_SESSION['aptno'] = $result['AptNumber'];
        echo "<script>window.location.href='thank-you.php'</script>";
    } else {
        $msg = "Algo salió mal. Inténtalo de nuevo";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>THE_KING_BARBERSHOP-Inicio</title>

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i" rel="stylesheet">


    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Incluye jQuery -->

    <!-- Incluye jQuery Timepicker -->
        <!-- CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">



</head>

<style>
    .no-select {
        user-select: none;
    }

    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #3498db;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f6fa;
    }

    .chatbox {
        width: 380px;
        position: fixed;
        bottom: 90px;
        right: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        z-index: 9999;
    }

    .card-header {
        background: var(--primary-color);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 15px;
    }

    .chatlogs {
        height: 400px;
        overflow-y: auto;
        padding: 15px;
        scrollbar-width: thin;
        scrollbar-color: var(--accent-color) #DDD;
    }

    .chatlogs::-webkit-scrollbar {
        width: 6px;
    }

    .chatlogs::-webkit-scrollbar-thumb {
        background: var(--accent-color);
        border-radius: 3px;
    }

    .user-message {
        background: #f8f9fa !important;
        border-radius: 15px 15px 0 15px !important;
        margin: 5px 0;
        padding: 10px 15px;
    }

    .bot-message {
        background: #e3f2fd !important;
        border-radius: 15px 15px 15px 0 !important;
        margin: 5px 0;
        padding: 10px 15px;
    }

    .typing-field {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 0 0 15px 15px;
    }

    .input-data {
        position: relative;
    }

    .form-control {
        border-radius: 20px;
        padding-right: 50px;
    }

    #sendBtn {
        position: absolute;
        right: 5px;
        top: 5px;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-floating {
        position: fixed;
        bottom: 20px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 9999;
    }

    .btn-floating:hover {
        transform: scale(1.1);
        background: var(--secondary-color);
    }

    .service-btn,
    .barber-btn,
    .time-btn {
        border-radius: 20px;
        margin: 5px;
        transition: all 0.3s ease;
    }

    .service-btn:hover,
    .barber-btn:hover,
    .time-btn:hover {
        transform: translateY(-2px);
    }

    .date-picker-container {
        margin: 10px 0;
    }

    .ui-datepicker {
        z-index: 10000 !important;
        font-size: 14px;
    }

    .datepicker {
        background-color: white !important;
        cursor: pointer !important;
        border-radius: 20px;
    }

    .close {
        color: white;
        opacity: 0.8;
    }

    .close:hover {
        color: white;
        opacity: 1;
    }

    @media (max-width: 480px) {
        .chatbox {
            width: 100%;
            height: 100vh;
            bottom: 0;
            right: 0;
            border-radius: 0;
            z-index: 9999;
        }

        .card-header {
            border-radius: 0 !important;
        }

        .btn-floating {
            bottom: 10px;
            right: 10px;
            z-index: 9999;
        }

        .chatlogs {
            height: calc(100vh - 130px);
        }
    }

    .hora-selector {
        margin: 15px 0;
    }

    .time-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 5px;
        margin-top: 10px;
    }

    .time-btn {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        text-align: center;
    }

    .time-btn.reserved {
        position: relative;
        cursor: not-allowed;
    }

    .time-btn.reserved::after {
        content: '✓';
        position: absolute;
        top: -5px;
        right: -5px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge {
        padding: 8px 12px;
        font-size: 12px;
        border-radius: 15px;
    }
</style>

<body>
    <?php include_once('includes/header.php'); ?>
    <!-- END nav -->

    
     

    <section id="home-section" class="hero" style="background-image: radial-gradient(circle, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.8) 100%), url(images/fondo_1.jpg);" data-stellar-background-ratio="0.5">
        <div class="home-slider owl-carousel">
            <div class="slider-item js-fullheight">
                <div class="container-fluid p-0">
                    <div class="overlay"></div>

                    <div class="row d-md-flex no-gutters slider-text align-items-end justify-content-end" data-scrollax-parent="true">
                        <div class="one-forth d-flex align-items-center ftco-animate" data-scrollax=" properties: { translateY: '10%' }">
                            <div class="text mt-5">
                                <span class="subheading" style="color: white;">THE KING BARBERSHOP</span>
                                <h1 class="mb-4" style="color: white;">Mas por ti</h1>
                                <p class="mb-4" style="color: white;">Esta barberia ofrecemos instalaciones con equipos de tecnología avanzada y un servicio de la mejor calidad. <br>le ofrecemos el mejor tratamiento que nunca haya experimentado antes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slider-item js-fullheight">
                <div class="overlay"></div>

                <div class="container-fluid p-0">
                    <div class="row d-flex no-gutters slider-text align-items-center justify-content-end" data-scrollax-parent="true">
                        <div class="one-forth d-flex align-items-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                            <div class="text mt-5">
                                <span class="subheading" style="color: white;">¡LUCE INPECABLE</span>
                                <h1 class="mb-4" style="color: white;">Siéntete Imparable!</h1>
                                <p class="mb-4" style="color: white;">Nuestra misión es hacerte destacar, la misión es ofrecerte un servicio de calidad excepcional, cuidando cada detalle de ti para que te sientas seguro en ti mismo. Usamos productos de primeras marcas, garantizando un estilo digno de reyes.<br>¡Descubre la excelencia!</p>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        
        
           


        
    </section>


    <br>
    <section class="ftco-section ftco-no-pt ftco-booking" style=" background: linear-gradient(to right, #d6d0cf, white);">
        <div class="container-fluid px-0">
            <div class="row no-gutters d-md-flex justify-content-end">
                <div class="one-forth d-flex align-items-end">
                    <div class="text">
                        <div class="overlay"></div>
                        <div class="appointment-wrap">
                            <span class="subheading">Reservaciones</span>
                            <h3 class="mb-2">Haga una cita</h3>
                            <form action="#" method="post" class="appointment-form">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" placeholder="Nombre" name="name" required="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="appointment_email" placeholder="Correo" name="email" required="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="select-wrap">
                                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                                <select name="services" id="services" required="true" class="form-control">
                                                    <option value="">Selecciona Nuestros Servicios</option>
                                                    <?php $query = mysqli_query($con, "select * from tblservices");
                                                    while ($row = mysqli_fetch_array($query)) {
                                                    ?>
                                                        <option value="<?php echo $row['ServiceName']; ?>"><?php echo $row['ServiceName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control appointment_date" placeholder="Fecha" name="adate" id='adate' required="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control appointment_time" placeholder="Hora" name="atime" id='atime' required="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Teléfono" required="true" maxlength="10" pattern="[0-9]+">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" value="HAZ UNA CITA" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="one-third">
                    <div class="img" style="background-image: url(images/barberos.jpg);">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Chatbox -->
        <div class="chatbox card" id="chatbox" style="display:none;">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>💇‍♂️ Asistente de Barbería</strong>
                    </div>
                    <button id="closeChat" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="chatlogs card-body" id="chatlogs">
                <!-- Los mensajes se agregarán aquí dinámicamente -->
            </div>
            <div class="typing-field">
                <div class="input-data">
                    <input type="text" id="userInput" class="form-control" placeholder="Escribe 'reservar cita' para comenzar..." required>
                    <button id="sendBtn" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Botón flotante -->
        <button id="floatingBtn" class="btn btn-floating">
            💬
        </button>
    </div>



<!-- Scripts -->
<!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <!-- jQuery and Timepicker --->
    <script>
        $(document).ready(function() {
            $('#atime').timepicker({
                timeFormat: 'h:i A', // Formato para incluir minutos y am/pm
                interval: 30, // Intervalo de 30 minutos
                minTime: '10:00am', // Hora mínima
                maxTime: '08:00pm', // Hora máxima
                defaultTime: '10:00am', // Hora predeterminada
                startTime: '10:00am', // Hora de inicio
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });


        $(document).ready(function() {
            // Variables globales
            let isTyping = false;
            const typingDelay = 50;
            const BOT_NAME = "Asistente";
            let chatInitialized = false;

            let selectedServices = [];
            let totalDuration = 0;
            let totalPrice = 0;

            // Función para mostrar mensaje de bienvenida
            function showWelcomeMessage() {
                // Reiniciar variables
                selectedServices = [];
                totalDuration = 0;
                totalPrice = 0;
                chatInitialized = false;

                // Limpiar el chat
                $('#chatlogs').empty();
                $('#userInput').val('');

                // Reiniciar la sesión y mostrar mensaje de bienvenida
                $.ajax({
                    url: 'reset_session.php',
                    type: 'POST',
                    success: function() {
                        setTimeout(() => {
                            appendMessage(BOT_NAME, "¡Hola! 👋 Soy el asistente virtual de la barbería. ¿En qué puedo ayudarte? Escribe 'reservar cita' para comenzar.", "bot");
                            chatInitialized = true;
                        }, 500);
                    }
                });
            }

            // Función para simular escritura del bot
            function typeMessage(message, element) {
                let i = 0;
                isTyping = true;

                function type() {
                    if (i < message.length) {
                        element.innerHTML += message.charAt(i);
                        i++;
                        setTimeout(type, typingDelay);
                    } else {
                        isTyping = false;
                    }
                }

                type();
            }

            // Función para agregar mensajes al chat
            function appendMessage(sender, message, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `${type === 'user' ? 'user-message' : 'bot-message'} alert alert-${type === 'user' ? 'secondary' : 'info'} no-select`;

                if (type === 'bot') {
                    messageDiv.innerHTML = message;
                    $('#chatlogs').append(messageDiv);
                } else {
                    messageDiv.textContent = message;
                    $('#chatlogs').append(messageDiv);
                }

                // Scroll al último mensaje
                $('#chatlogs').scrollTop($('#chatlogs')[0].scrollHeight);
            }

            // Función para enviar mensajes
            window.sendMessage = function(message) {
                if (isTyping || !chatInitialized) return;

                const isInternalCommand = message === 'CONFIRMAR_SERVICIOS';

                if (!isInternalCommand) {
                    appendMessage('Usuario', message, 'user');
                }

                $('#userInput').prop('disabled', true);

                $.ajax({
                    url: 'chat.php',
                    type: 'POST',
                    data: {
                        text: message
                    },
                    success: function(response) {
                        if (response === '_INTERNAL_UPDATE_') {
                            $('#userInput').prop('disabled', false);
                            return;
                        }

                        appendMessage(BOT_NAME, response, 'bot');
                        $('#userInput').prop('disabled', false);
                        $('#userInput').focus();

                        if (response.includes('datePicker')) {
                            initializeDatepicker();
                        }
                    },
                    error: function() {
                        appendMessage(BOT_NAME, "Lo siento, ha ocurrido un error. Por favor, intenta nuevamente.", 'bot');
                        $('#userInput').prop('disabled', false);
                    }
                });
            };

            // Inicializar datepicker
            function initializeDatepicker() {
                $(".datepicker").datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0,
                    maxDate: '+2M',
                    beforeShowDay: $.datepicker.noWeekends,
                    onSelect: function(dateText) {
                        sendMessage(dateText);
                        $(this).datepicker('destroy');
                    }
                });
            }

            // Función para confirmar servicios
            window.confirmarServicios = function() {
                if (selectedServices.length > 0) {
                    sendMessage(selectedServices.join(','));
                    sendMessage('CONFIRMAR_SERVICIOS');
                } else {
                    appendMessage(BOT_NAME, 'Por favor, selecciona al menos un servicio.', 'bot');
                }
            };

            // Función para alternar servicios
            window.toggleService = function(serviceName, price, duration) {
                const serviceBtn = document.querySelector(`[data-service='${serviceName}']`);
                const serviceIndex = selectedServices.findIndex(s => s === serviceName);

                if (serviceIndex > -1) {
                    selectedServices.splice(serviceIndex, 1);
                    totalDuration -= duration;
                    totalPrice -= price;
                    serviceBtn.classList.remove('active', 'btn-primary');
                    serviceBtn.classList.add('btn-outline-primary');
                } else {
                    selectedServices.push(serviceName);
                    totalDuration += duration;
                    totalPrice += price;
                    serviceBtn.classList.remove('btn-outline-primary');
                    serviceBtn.classList.add('active', 'btn-primary');
                }

                updateServicesDisplay();
            };

            // Función para actualizar la visualización de servicios
            function updateServicesDisplay() {
                const selectedServicesDiv = document.getElementById('selected-services');
                const servicesList = document.getElementById('services-list');
                const totalDurationSpan = document.getElementById('total-duration');
                const totalPriceSpan = document.getElementById('total-price');

                if (selectedServices.length > 0) {
                    selectedServicesDiv.style.display = 'block';
                    servicesList.textContent = selectedServices.join(', ');
                    totalDurationSpan.textContent = totalDuration;
                    totalPriceSpan.textContent = totalPrice.toFixed(2);
                } else {
                    selectedServicesDiv.style.display = 'none';
                }
            }

            // Event Listeners
            $('#floatingBtn').click(function() {
                $('#chatbox').toggle();
                if ($('#chatbox').is(':visible')) {
                    $('#userInput').focus();
                    if (!chatInitialized) {
                        showWelcomeMessage();
                    }
                }
            });

            $('#closeChat').click(function() {
                $('#chatbox').hide();
                showWelcomeMessage();
            });

            $('#sendBtn').click(function() {
                const userInput = $('#userInput').val().trim();
                if (userInput && !isTyping && chatInitialized) {
                    sendMessage(userInput);
                    $('#userInput').val('');
                }
            });

            $('#userInput').keypress(function(e) {
                if (e.which == 13) {
                    $('#sendBtn').click();
                }
            });

            // Observer para datepicker
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1 && node.querySelector('.datepicker')) {
                                initializeDatepicker();
                            }
                        });
                    }
                });
            });

            observer.observe(document.getElementById('chatlogs'), {
                childList: true,
                subtree: true
            });

            // Manejador de eventos para cuando se cierra la ventana o pestaña
            $(window).on('beforeunload', function() {
                // Limpiar el chat
                $('#chatlogs').empty();
                $('#userInput').val('');

                // Destruir la sesión
                $.ajax({
                    url: 'reset_session.php',
                    type: 'POST',
                    async: false
                });
            });
        });
    </script>
    <br>
    <?php include_once('includes/footer.php'); ?>



    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
        </svg></div>



    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="js/google-map.js"></script>
    <script src="js/main.js"></script>

</body>

</html>