<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneos - Mi Liga</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .filter-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f1f8ff;
            /* Un azul claro para la sección de filtros */
            border-radius: 8px;
            border: 1px solid #cfe2ff;
        }

        .section-title {
            margin-top: 40px;
            margin-bottom: 20px;
            color: #007bff;
        }

        /* Estilos para Banners */
        .banner-horizontal {
            width: 100%;
            height: 100px;
            /* Altura sugerida para banners horizontales */
            background-color: #e0f2f7;
            /* Un color claro para diferenciar */
            border: 1px dashed #90caf9;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 1.2rem;
            color: #424242;
            margin-bottom: 30px;
            /* Espacio debajo del banner */
            overflow: hidden;
            /* Asegura que el contenido del banner no se desborde */
        }

        .banner-vertical {
            width: 100%;
            height: 300px;
            /* Altura sugerida para banners verticales en un sidebar */
            background-color: #e0f7fa;
            /* Otro color para diferenciar */
            border: 1px dashed #80deea;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 1.1rem;
            color: #424242;
            margin-bottom: 20px;
            /* Espacio entre banners verticales */
            overflow: hidden;
        }

        /* Ajustes para el diseño con sidebar de banners verticales */
        @media (min-width: 992px) {

            /* Para pantallas grandes (lg) y superiores */
            .main-content {
                padding-right: 15px;
                /* Espacio entre el contenido principal y el sidebar */
            }

            .sidebar-banners {
                padding-left: 15px;
                /* Espacio entre el sidebar y el contenido principal */
            }
        }

        .team-header {
            background-color: #f8f9fa;
            padding: 30px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
        }

        .team-logo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .section-card-header {
            background-color: #007bff;
            color: white;
        }

        .player-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
            border: 1px solid #ddd;
        }

        .fixture-card {
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .fixture-header {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .fixture-body {
            padding: 15px;
        }

        .team-name-small {
            font-size: 0.9em;
            /* Makes team names smaller in fixture display */
        }

        .score {
            font-size: 1.5em;
            font-weight: bold;
        }

        .team-vs {
            font-size: 14px;
            font-weight: bold;
            color: #343a40;
        }

        .list-group-item.scorer-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }

        .scorer-item .player-info {
            display: flex;
            align-items: center;
        }

        .scorer-item .goals-badge {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('front.home') }}">
                <img src="{{ asset('front/images/liga cafetera.png') }}" alt="LIGA CAFETERA" width="20"
                    height="20" class="me-2 rounded-circle">
                Liga Cafetera
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="https://ascar.org.ar/">
                             <img src="https://ascar.org.ar/wp-content/uploads/2025/06/Frame-290109.png" alt="ASCARA" width="" height="30" class="me-2">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <footer class="bg-light text-center text-lg-start mt-5 py-3">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Mi Liga. Desarrollada y diseñada por <a href="https://miguellongart.com"
                    target="_blank" rel="noopener noreferrer" class="text-decoration-none text-primary fw-bold">Miguel
                    Longart</a>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.2/dist/sweetalert2.all.min.js"></script>
    @yield('script')
</body>

</html>
