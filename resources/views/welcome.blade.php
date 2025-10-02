@extends('layouts.app')

@section('title', 'Accueil - ZENDO')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Vos colis, partout et en toute sécurité, d'un simple clic</h1>
            <p class="lead mb-4">Télécharger notre application pour devenir livreur ou expédier vos colis en toute sécurité</p>
            
            <div class="app-download-icons mt-4">
                <img src="{{ asset('publicpic/image 1.png') }}" alt="Download on App Store" class="me-3" style="height: 60px;">
                <img src="{{ asset('publicpic/image 2.png') }}" alt="Get it on Google Play" style="height: 60px;">
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/livreuraccueil.png') }}" alt="Livreur ZENDO" class="img-fluid" style="max-height: 700px;">
        </div>
    </div>
    
    <div class=" p-4 mb-4">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                    <h1 class="page-title text-center display-4 fw-bold">Ce qui rend Zendo unique</h1>

                <p class="lead mb-4">
                    une application qui simplifie vos livraisons et ouvre des opportunités aux livreurs indépendants.
                </p>
              
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #FFD800;"></i>
                    </div>
                    <h5 class="card-title">Rapidité</h5>
                    <p class="card-text">Vos colis livrés en un temps record grâce à nos livreurs disponibles partout. Un suivi en temps réel et une livraison fiable à chaque étape.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt" style="font-size: 3rem; color: #FFD800;"></i>
                    </div>
                    <h5 class="card-title">Sécurité</h5>
                    <p class="card-text">Expédiez et suivez vos colis en quelques clics depuis l'application.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-mouse-pointer" style="font-size: 3rem; color: #FFD800;"></i>
                    </div>
                    <h5 class="card-title">Simplicité</h5>
                    <p class="card-text">Des prix transparents et accessibles, sans frais cachés.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign" style="font-size: 3rem; color: #FFD800;"></i>
                    </div>
                    <h5 class="card-title">Tarifs justes</h5>
                    <p class="card-text">Des prix transparents et accessibles, sans frais cachés.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section Nos solutions -->
    <div class="row align-items-center py-5 mt-5"> <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/receptioncolis.png') }}" alt="Nos solutions ZENDO" class="img-fluid" style="max-height: 600px;">
        </div>
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4">Nos solutions</h2>
            <p class="mb-4">Zendo simplifie vos envois, que vous soyez particulier ou professionnel. Expédiez vos colis rapidement, simplement et en toute sécurité partout en Côte d'Ivoire, en Afrique et dans le monde.</p>
            
            <p class="mb-4">Notre application connecte ceux qui envoient des colis avec des livreurs indépendants prêts à intervenir à tout moment, où que vous soyez. Découvrez la solution qui vous correspond et profitez d'une livraison sans tracas.</p>
            
            <a href="{{ route('services') }}" class="btn  btn-lg px-4 py-2" style="background: #FFD800;">Voir nos services</a>
        </div>
       
    </div>
</div>

<!-- Section Avis clients -->
<div class="py-5" >
    <div class="container" style="background-color: #FFD800;padding: 20px;border-radius: 20px;">
        <div class="row">
            <div class="col-lg-5">
                <br><br><br>
                <h2 class="display-5 fw-bold mb-4" style="color: #333;">Découvrez ce que nos clients et livreurs pensent de Zendo.</h2>
                
                <!-- Google Rating -->
                <div class="d-flex align-items-center mb-4 p-3 bg-white rounded shadow-sm">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google" style="height: 30px;" class="me-3">
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <!-- Témoignage 1 -->
                <div  style="background-color: #F4D150;" class=" rounded p-4 mb-3 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-secondary" style="width: 50px; height: 50px; background-image: url('https://via.placeholder.com/50'); background-size: cover;"></div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">Marie K., Abidjan</h6>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0">Grâce à Zendo, j'ai pu expédier mon colis en quelques clics et le recevoir rapidement. Une application vraiment pratique !</p>
                </div>
                
                <!-- Témoignage 2 -->
                <div  style="background-color: #F4D150;" class=" rounded p-4 mb-3 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-secondary" style="width: 50px; height: 50px; background-image: url('https://via.placeholder.com/50'); background-size: cover;"></div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">Ahmed T., Yopougon</h6>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0">En tant que livreur indépendant, Zendo m'a permis de lancer mon activité facilement et de trouver des clients fiables.</p>
                </div>
                
                <!-- Témoignage 3 -->
                <div  style="background-color: #F4D150;" class=" rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-secondary" style="width: 50px; height: 50px; background-image: url('https://via.placeholder.com/50'); background-size: cover;"></div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">Sophie L., Bouaké</h6>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0">Service impeccable, suivi en temps réel et tarifs transparents. Je recommande Zendo à tous ceux qui veulent envoyer.</p>
                </div>
            </div>
        </div>
    </div>
</div>

 <center><h2 class="display-4 fw-bold mb-4">Comment ça marche ? </h2></center>

<!-- Section Étape 1 -->
<div class="container my-5">
    <div class="row align-items-center" style="border-radius: 20px; margin: 0; overflow: hidden;">
        <div class="col-lg-6" style="background: #F1F1F1; padding: 60px 40px;">
            <div class="text-dark">
                <span class="badge bg-warning text-dark px-3 py-2 mb-3" style="border-radius: 20px; font-size: 14px;">Étape 1</span>
                <h2 class="display-5 fw-bold mb-4">Téléchargez l'application</h2>
                <p class="lead mb-4" style="font-size: 18px; line-height: 1.6;">
                    Installez Zendo sur votre smartphone<br>
                    et accédez à toutes nos fonctionnalités<br>
                    de livraison.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none">
                        <img src="{{ asset('publicpic/image 1.png') }}" alt="Télécharger dans l'App Store" style="height: 50px;">
                    </a>
                    <a href="#" class="text-decoration-none">
                        <img src="{{ asset('publicpic/image 2.png') }}" alt="Disponible sur Google Play" style="height: 50px;">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 text-center" style="background: #FFD800; padding: 20px 20px;">
            <img src="{{ asset('publicpic/Photo2 1.png') }}" alt="Application Zendo" class="img-fluid" >
        </div>
    </div>

<br><br>

    <!-- Section Étape 2 -->
    <div class="row align-items-center" style="border-radius: 20px; margin: 0; overflow: hidden;">
       
        <div class="col-lg-6" style="background: #F1F1F1; padding: 60px 40px;">
            <div class="text-dark">
                <span class="badge bg-warning text-dark px-3 py-2 mb-3" style="border-radius: 20px; font-size: 14px;">Étape 2</span>
                <h2 class="display-5 fw-bold mb-4">Inscrivez-vous</h2>
                <p class="lead mb-4" style="font-size: 18px; line-height: 1.6;">
                    Créez votre compte en tant qu'expéditeur<br>
                    ou coursier. Votre inscription sera<br>
                    validée rapidement.
                </p>
            </div>    
             <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none">
                        <img src="{{ asset('publicpic/image 1.png') }}" alt="Télécharger dans l'App Store" style="height: 50px;">
                    </a>
                    <a href="#" class="text-decoration-none">
                        <img src="{{ asset('publicpic/image 2.png') }}" alt="Disponible sur Google Play" style="height: 50px;">
                    </a>
                </div>
         </div>
             <div class="col-lg-6" style="background: #FFD800; padding: 20px 20px;">
            <div class="text-center">
                <img src="{{ asset('publicpic/Photo2 1.png') }}" alt="Inscription Zendo" class="img-fluid">
            </div>
   
        </div>
    </div>

<br><br>

    <!-- Section Étape 3 -->
    <div class="row align-items-center" style="border-radius: 20px; margin: 0; overflow: hidden;">
        <div class="col-lg-6" style="background: #F1F1F1; padding: 60px 40px;">
            <div class="text-dark">
                <span class="badge bg-warning text-dark px-3 py-2 mb-3" style="border-radius: 20px; font-size: 14px;">Étape 3</span>
                <h2 class="display-5 fw-bold mb-4">Commencez vos opérations</h2>
                <p class="lead mb-4" style="font-size: 18px; line-height: 1.6;">
                    Une fois validé, commencez à envoyer ou<br>
                    livrer vos colis facilement et en toute<br>
                    sécurité avec Zendo.
                </p>
            </div>
             <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none">
                        <img src="{{ asset('publicpic/image 1.png') }}" alt="Télécharger dans l'App Store" style="height: 50px;">
                    </a>
                    <a href="#" class="text-decoration-none">
                        <img src="{{ asset('publicpic/image 2.png') }}" alt="Disponible sur Google Play" style="height: 50px;">
                    </a>
                </div>
        </div>
        <div class="col-lg-6 text-center" style="background: #FFD800; padding: 50px 20px;">
            <img src="{{ asset('publicpic/Photo2 1.png') }}" alt="Opérations Zendo" class="img-fluid">
        </div>
    </div>
<br><br>

</div>

@endsection
