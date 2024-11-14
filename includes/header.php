<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navegación Oculta</title>
  <!-- Bootstrap CSS -->

  <!-- Estilos para la barra de navegación -->
  <style>
    /* Estilos generales para la barra de navegación */
    #ftco-navbar {
      background-color: rgba(0, 0, 0, 0.9);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 9999;
      transition: top 0.5s ease, background-color 0.3s ease;
    }

    /* Enlace activo */
    .navbar-nav .nav-link.active {
      color: red !important;
    }

    /* Otros estilos */
    .navbar-toggler {
      border-color: rgba(255, 255, 255, 0.5);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    /* Cambia el color de fondo al hacer scroll */
    .scrolled {
      background-color: rgba(0, 0, 0, 0.9) !important;
    }

    /* Ocultar barra de navegación al hacer scroll hacia abajo */
    .hidden {
      top: -80px;
    }

    /* Alineación y estilo de los enlaces */
    .navbar-nav {
      margin-left: auto;
    }

    .navbar-nav .nav-link {
      color: white;
      position: relative;
      z-index: 2;
      transition: color 0.3s ease;
      margin: 0 20px;
    }

    .navbar-nav .nav-link:hover {
      color: red;
    }

    /* Subrayado animado en los enlaces */
    .navbar-nav .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      background-color: red;
      bottom: -5px;
      left: 0;
      transition: width 0.3s ease;
    }

    .navbar-nav .nav-link:hover::after {
      width: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
      .navbar-nav {
        flex-direction: column;
        text-align: center;
      }

      .navbar-nav .nav-item {
        margin: 10px 0;
      }
    }
  </style>
</head>

<body>

  <!-- Navegación -->
  <nav class="navbar navbar-expand-lg" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="images/logo_edit.png" alt="Logo" style="height: 60px; width: 110px;">
      </a>
      <!-- Botón de hamburguesa para pantallas pequeñas -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Inicio</a></li>
          <li class="nav-item"><a href="services.php" class="nav-link">Servicios</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">Acerca de</a></li>
          <li class="nav-item"><a href="clientes.php" class="nav-link">Clientes</a></li>
          <li class="nav-item"><a href="contact.php" class="nav-link">Contacto</a></li>
          <li class="nav-item"><a href="admin/index.php" class="nav-link">Admin</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- JavaScript para el comportamiento del menú al hacer scroll y para resaltar el enlace activo -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
      const navbarToggler = document.querySelector('.navbar-toggler');
      const navbarCollapse = document.querySelector('.navbar-collapse');

      // Función para resaltar el enlace activo
      function setActiveLink() {
        navLinks.forEach(link => {
          if (link.href === window.location.href) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
      }

      // Al cargar la página, resaltar el enlace activo
      setActiveLink();

      // Agrega un evento de clic a cada enlace de navegación
      navLinks.forEach(link => {
        link.addEventListener('click', function() {
          // Elimina la clase 'active' de todos los enlaces
          navLinks.forEach(link => link.classList.remove('active'));
          // Agrega la clase 'active' al enlace seleccionado
          this.classList.add('active');

          // Oculta el menú en dispositivos pequeños al hacer clic en un enlace
          if (navbarCollapse.classList.contains('show')) {
            navbarToggler.click();
          }
        });
      });
    });

    let lastScrollTop = 0;
    const header = document.getElementById('ftco-navbar');
    let isScrolling;

    window.addEventListener('scroll', function() {
      const scrollPosition = window.scrollY;

      if (scrollPosition > lastScrollTop) {
        // Scroll hacia abajo
        header.classList.add('hidden'); // Ocultar el menú
      } else {
        // Scroll hacia arriba
        header.classList.remove('hidden'); // Mostrar el menú
      }

      lastScrollTop = scrollPosition;

      // Reinicia el temporizador
      clearTimeout(isScrolling);
      isScrolling = setTimeout(() => {
        header.classList.remove('hidden'); // Mostrar el menú después de detener el scroll
      }, 150);
    });
  </script>



  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>