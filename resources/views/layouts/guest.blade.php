<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga Cafetera Ascar - Torneo de Fútbol en Buenos Aires</title>

    <meta name="description" content="Liga Cafetera Ascar: Torneo de fútbol amateur en Buenos Aires, Argentina. Compite, disfruta y sigue los resultados, equipos y goleadores de nuestra liga. ¡Únete a la pasión del fútbol!">
    <meta name="keywords" content="torneo de futbol, liga cafetera, ascar, buenos aires, futbol amateur, liga de futbol, torneo, futbol, argentina, resultados, equipos, goleadores">
    <meta name="author" content="Ascar">

    <meta property="og:title" content="Liga Cafetera Ascar - ¡El torneo de fútbol en Buenos Aires!">
    <meta property="og:description" content="Sigue los resultados, equipos y goleadores de nuestra emocionante liga de fútbol amateur en Buenos Aires, Argentina. ¡Únete a la pasión del juego!">
    <meta property="og:image" content="{{ asset('front/images/ascar.png') }}">
    <meta property="og:url" content="URL_DE_TU_PAGINA">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_ES">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@TU_USUARIO_DE_TWITTER">
    <meta name="twitter:creator" content="@TU_USUARIO_DE_TWITTER">
    <meta name="twitter:title" content="Liga Cafetera Ascar: ¡Fútbol en Buenos Aires!">
    <meta name="twitter:description" content="Entérate de todo sobre la Liga Cafetera Ascar, el torneo de fútbol amateur en Buenos Aires. Resultados, equipos, y más.">
    <meta name="twitter:image" content="{{ asset('front/images/ascar.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('front/images/ascar.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('front/images/ascar.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('front/images/ascar.png') }}" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset('front/images/ascar.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('front/images/ascar.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('front/images/ascar.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">

    @if(config('services.google.adsense_id'))
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.google.adsense_id') }}"
            crossorigin="anonymous"></script>
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Variables CSS para tema moderno */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Estilos globales mejorados */
        body {
            background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Cards modernos con glassmorphism */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .card-header {
            background: var(--primary-gradient);
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: white !important;
        }

        .card-header span {
            color: white !important;
        }

        .card-header.bg-success {
            background: var(--success-gradient) !important;
        }

        .card-header.bg-danger {
            background: var(--danger-gradient) !important;
        }

        .card-header.bg-warning {
            background: var(--warning-gradient) !important;
        }

        .card-header.bg-dark {
            background: var(--dark-gradient) !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Sección de filtros moderna */
        .filter-section {
            margin-bottom: 30px;
            padding: 25px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: var(--border-radius);
            border: 2px solid rgba(102, 126, 234, 0.2);
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
        }

        .filter-section h3 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Botones modernos */
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Form controls modernos */
        .form-select, .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        .form-select:focus, .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .section-title {
            margin-top: 40px;
            margin-bottom: 30px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .table-responsive {
            margin-top: 20px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        /* Estilos para Banners */
        .banner-horizontal {
            width: 100%;
            height: 100px;
            background-color: #252525;
            border: 1px dashed #fdba00;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 1.2rem;
            color: #424242;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .banner-vertical {
            width: 100%;
            height: 300px;
            background-color: #252525;
            border: 1px dashed #fdba00;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 1.1rem;
            color: #424242;
            margin-bottom: 20px;
            overflow: hidden;
            transition: var(--transition);
        }

        .banner-vertical:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-md);
        }

        /* Tabla moderna */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            border: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table tbody tr {
            transition: var(--transition);
            border: none;
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f3f5;
        }

        /* List group moderno */
        .list-group-item {
            border: none;
            border-left: 3px solid transparent;
            margin-bottom: 0.5rem;
            border-radius: 8px !important;
            transition: var(--transition);
            background: #f8f9fa;
        }

        .list-group-item:hover {
            border-left-color: #667eea;
            background: white;
            transform: translateX(5px);
            box-shadow: var(--shadow-sm);
        }

        /* Badges modernos */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .badge.bg-success {
            background: var(--success-gradient) !important;
        }

        .badge.bg-danger {
            background: var(--danger-gradient) !important;
        }

        .badge.bg-warning {
            background: var(--warning-gradient) !important;
            color: white !important;
        }

        .badge.bg-info {
            background: var(--info-gradient) !important;
        }

        .badge.bg-dark {
            background: var(--dark-gradient) !important;
        }

        /* Ajustes para el diseño con sidebar de banners verticales */
        @media (min-width: 992px) {
            .main-content {
                padding-right: 15px;
            }

            .sidebar-banners {
                padding-left: 15px;
            }
        }

        .team-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 40px 0;
            margin-bottom: 30px;
            border-bottom: 3px solid;
            border-image: var(--primary-gradient) 1;
            text-align: center;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }

        .team-logo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid transparent;
            background: var(--primary-gradient);
            padding: 3px;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .team-logo:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .section-card-header {
            background: var(--primary-gradient);
            color: white;
        }

        .player-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #667eea;
            transition: var(--transition);
        }

        .player-img:hover {
            transform: scale(1.2);
            border-color: #764ba2;
        }

        /* Fixture cards modernos */
        .fixture-card {
            margin-bottom: 15px;
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            background: white;
        }

        .fixture-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .fixture-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 12px 15px;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fixture-body {
            padding: 20px 15px;
            background: white;
        }

        .team-name-small {
            font-size: 0.85em;
            font-weight: 600;
            color: #2c3e50;
        }

        .score {
            font-size: 2em;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .team-vs {
            font-size: 16px;
            font-weight: 700;
            color: #95a5a6;
            padding: 0 10px;
        }

        /* Lista de goleadores mejorada */
        .list-group-item.scorer-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border: none;
            border-left: 3px solid transparent;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .list-group-item.scorer-item:hover {
            background: white;
            border-left-color: #ee0979;
            transform: translateX(5px);
            box-shadow: var(--shadow-sm);
        }

        .scorer-item .player-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .scorer-item .goals-badge {
            font-size: 16px;
            font-weight: 700;
            padding: 0.5rem 1rem;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .filter-section, .fixture-card {
            animation: fadeIn 0.5s ease-out;
        }

        /* Alerts modernos */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border-left: 4px solid;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
            border-left-color: #4facfe;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(238, 9, 121, 0.1) 0%, rgba(255, 106, 0, 0.1) 100%);
            border-left-color: #ee0979;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);
            border-left-color: #f093fb;
        }

        /* Navbar moderno */
        .navbar {
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
            background: rgba(33, 37, 41, 0.95) !important;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            transition: var(--transition);
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
