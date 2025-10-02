@extends('layouts.app')

@section('title', 'Expéditeur - ZENDO')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Envoyez vos colis 
partout, en toute 
simplicité</h1>
            <p class="lead mb-4">
Que vous soyez un particulier ou une entreprise, Zendo 
vous permet d’envoyer vos colis en quelques clics.            </p>
            
            <div class="app-download-icons mt-4">
                <img src="{{ asset('publicpic/image 1.png') }}" alt="Download on App Store" class="me-3" style="height: 60px;">
                <img src="{{ asset('publicpic/image 2.png') }}" alt="Get it on Google Play" style="height: 60px;">
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/livreuraccueil.png') }}" alt="Livreur ZENDO" class="img-fluid" style="max-height: 700px;">
        </div>
    </div>

    
    <!-- Section Nos solutions -->
    <div class="row align-items-center py-5 mt-5"> <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/livreursouris.png') }}" alt="Nos solutions ZENDO" class="img-fluid" style="max-height: 600px;">
        </div>
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4">Nos solutions</h2>
            <p class="mb-4">Zendo simplifie vos envois, que vous soyez particulier ou professionnel. Expédiez vos colis rapidement, simplement et en toute sécurité partout en Côte d'Ivoire, en Afrique et dans le monde.</p>
            
            <p class="mb-4">Notre application connecte ceux qui envoient des colis avec des livreurs indépendants prêts à intervenir à tout moment, où que vous soyez. Découvrez la solution qui vous correspond et profitez d'une livraison sans tracas.</p>
            
            <a href="{{ route('services') }}" class="btn  btn-lg px-4 py-2" style="background: #FFD800;">Voir nos services</a>
        </div>
       
    </div>
</div>

<center>            <h2 class="display-5 fw-bold mb-4">Comment devenir expéditeur ?</h2>
</center>

<!-- Section Comment ça marche -->
<div class="container my-5">
    <div class="row align-items-center" style="background: #F8F9FA; border-radius: 20px; padding: 60px 40px; margin: 0;">
        <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/Photo2 1.png') }}" alt="Application Zendo" class="img-fluid" style="max-height: 400px;">
        </div>
        <div class="col-lg-6">
            <div class="steps-container">
                <!-- Étape 1 -->
                <div class="d-flex align-items-start mb-4">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">1</div>
                    <div>
                        <h5 class="fw-bold mb-2">Téléchargez l'application</h5>
                        <p class="text-muted mb-0">Créez votre compte expéditeur</p>
                    </div>
                </div>
                
                <!-- Étape 2 -->
                <div class="d-flex align-items-start mb-4">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">2</div>
                    <div>
                        <h5 class="fw-bold mb-2">Indiquez les détails de votre colis</h5>
                        <p class="text-muted mb-0">Taille, poids, adresses</p>
                    </div>
                </div>
                
                <!-- Étape 3 -->
                <div class="d-flex align-items-start mb-4">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">3</div>
                    <div>
                        <h5 class="fw-bold mb-2">Choisissez un coursier disponible</h5>
                        <p class="text-muted mb-0">Confirmez l'envoi</p>
                    </div>
                </div>
                
                <!-- Étape 4 -->
                <div class="d-flex align-items-start">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">4</div>
                    <div>
                        <h5 class="fw-bold mb-2">Suivez la livraison</h5>
                        <p class="text-muted mb-0">Jusqu'à la réception</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection