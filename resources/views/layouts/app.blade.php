<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ZENDO - Service de Livraison')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar-brand {
            color: black !important;
            font-weight: bold;
            font-size: 2rem;
        }
        
        .navbar-nav .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: #FFD800 !important;
        }
        
        .navbar-nav .nav-link.active {
            background-color: #FFD800;
            color: black !important;
            border-radius: 5px;
        }
        
        .page-title {
            color: #333;
            margin-bottom: 2rem;
        }
        
        .content-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        body {
            background-color: #FFFFFFFF;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                <!-- <img src="{{ asset('adminpic/logo.png') }}" alt="ZENDO Logo" height="40" width="140" class="me-2"> -->
                ZENDO
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('expediteur') ? 'active' : '' }}" href="{{ route('expediteur') }}">Expéditeur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('coursier') ? 'active' : '' }}" href="{{ route('coursier') }}">Coursier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">Nos services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact nous</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-black text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- Newsletter Section -->
                <div class="col-lg-6 mb-4">
                    <h4 class="mb-3">Abonnez-vous à notre newsletter</h4>
                    <div class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Entrer votre adresse" style="border-radius: 5px;">
                        <button class="btn px-4" style="background-color: #FFD800; color: black; font-weight: bold; border-radius: 5px;">Envoyer</button>
                    </div>
                </div>
                
                <!-- Social Media Section -->
                <div class="col-lg-6 mb-4 text-lg-end">
                    <h4 class="mb-3">Rejoignez-nous</h4>
                    <div class="d-flex justify-content-lg-end gap-3">
                        <a href="#" class="text-white fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: #444;">
            
            <!-- Pages Section -->
            <div class="text-center mb-4">
                <h5 class="mb-3" style="text-decoration: underline;">Pages</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
                            <a href="{{ route('coursier') }}" class="text-white text-decoration-none">Devenez Coursier</a>
                            <span class="text-muted">|</span>
                            <a href="{{ route('expediteur') }}" class="text-white text-decoration-none">Devenez Expéditeur</a>
                            <span class="text-muted">|</span>
                            <a href="{{ route('about') }}" class="text-white text-decoration-none">A propos</a>
                            <span class="text-muted">|</span>
                            <a href="{{ route('faq') }}" class="text-white text-decoration-none">FAQ</a>
                            <span class="text-muted">|</span>
                            <a href="#" class="text-white text-decoration-none">Politique de cookies</a>
                            <span class="text-muted">|</span>
                            <a href="#" class="text-white text-decoration-none">Notre engagement</a>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-4">
                            <a href="#" class="text-white text-decoration-none">Mentions légales</a>
                            <span class="text-muted">|</span>
                            <a href="#" class="text-white text-decoration-none">Politique de Confidentialité</a>
                            <span class="text-muted">|</span>
                            <a href="#" class="text-white text-decoration-none">Conditions Générales d'Utilisation</a>
                            <span class="text-muted">|</span>
                            <a href="#" class="text-white text-decoration-none">Gestion des litigs</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: #444;">
            
            <!-- Copyright -->
            <div class="text-center">
                <p class="mb-0">© Zendo</p>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>