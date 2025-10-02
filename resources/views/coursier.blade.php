@extends('layouts.app')

@section('title', 'Coursier - ZENDO')

@section('content')

<div class="container mt-5">
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Travaillez en toute 
liberté, gagnez en 
toute sécurité.</h1>
            <p class="lead mb-4">
Avec Zendo, vous avez l’opportunité de devenir coursier 
indépendant et de générer des revenus selon vos disponibilités.         </p>
            
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
            <h2 class="display-5 fw-bold mb-4">Pourquoi devenir 
coursier Zendo ?</h2>
            <p class="mb-4">Être coursier chez Zendo, c’est bien plus qu’un simple 
travail : c’est une opportunité de liberté et de flexibilité. 
Vous êtes maître de votre emploi du temps, vous 
choisissez vos missions et travaillez quand vous 
le souhaitez. Chaque livraison vous permet de 
générer des revenus, tout en bénéficiant d’une 
plateforme sécurisée qui vérifie les expéditeurs 
et garantit vos paiements. Grâce à une application.</p>
            
            <a href="{{ route('services') }}" class="btn  btn-lg px-4 py-2" style="background: #FFD800;">Devenez coursier</a>
        </div>
       
    </div>
</div>

<center>            <h2 class="display-5 fw-bold mb-4">Comment devenir coursier ?</h2>
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
                        <h5 class="fw-bold mb-2">Téléchargez l’application Zendo</h5>
                        <p class="text-muted mb-0">sur votre smartphone</p>
                    </div>
                </div>
                
                <!-- Étape 2 -->
                <div class="d-flex align-items-start mb-4">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">2</div>
                    <div>
                        <h5 class="fw-bold mb-2">Inscrivez-vous en tant que coursier</h5>
                        <p class="text-muted mb-0">En remplissant vos informations et en envoyant vos documents requis.</p>
                    </div>
                </div>
                
                <!-- Étape 3 -->
                <div class="d-flex align-items-start mb-4">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">3</div>
                    <div>
                        <h5 class="fw-bold mb-2">Attendez la validation</h5>
                        <p class="text-muted mb-0">De votre profil par notre équipe.</p>
                    </div>
                </div>
                
                <!-- Étape 4 -->
                <div class="d-flex align-items-start">
                    <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">4</div>
                    <div>
                        <h5 class="fw-bold mb-2">Commencez vos missions</h5>
                        <p class="text-muted mb-0">Effectuez vos premières livraisons.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <center>    <img src="{{ asset('publicpic/image.png') }}" alt="Download on App Store" class="me-3" style="height: px;"></center>

@endsection