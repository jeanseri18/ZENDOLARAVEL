@extends('layouts.app')

@section('title', 'À propos - ZENDO')

@section('content')
<div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0">
                    <div class="card-body p-5 text-center">
                        <h1 class="fw-bold mb-4" style="color: black;">À propos de nous</h1>
                        <p class="lead text-muted mb-0">
                            Zendo est une application de livraison innovante qui connecte expéditeurs 
                            et coursiers pour rendre l'envoi de colis simple, rapide et sécurisé. 
                            Notre objectif est de faciliter la vie de tous, qu'il s'agisse de particuliers 
                            ou d'entreprises, en Côte d'Ivoire, en Afrique et dans le monde.
                        </p>
                    </div>
                </div>
            </div>
        </div>


        
          <div class="row align-items-center py-5 mt-5"> <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/apropos.png') }}" alt="Nos solutions ZENDO" class="img-fluid" style="max-height: 500px;">
        </div>
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4">Nos solutions </h2>
            <p class="mb-4">Offrir une solution de livraison fiable et accessible, 
qui permette à chacun d’envoyer et de recevoir 
des colis facilement tout en créant des opportunités 
de revenus pour les coursiers indépendants.</p>
            
            <a href="{{ route('services') }}" class="btn  btn-lg px-4 py-2" style="background: #FFD800;">Devenez coursier</a>
        </div>
        </div>
       


        <div class="row align-items-center py-5 mt-5"> 
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4">Nos solutions </h2>
            <p class="mb-4">Offrir une solution de livraison fiable et accessible, 
qui permette à chacun d’envoyer et de recevoir 
des colis facilement tout en créant des opportunités 
de revenus pour les coursiers indépendants.</p>
            
            <a href="{{ route('services') }}" class="btn  btn-lg px-4 py-2" style="background: #FFD800;">Devenez coursier</a>
        </div>
        <div class="col-lg-6 text-center">
            <img src="{{ asset('publicpic/apropos1.png') }}" alt="Nos solutions ZENDO" class="img-fluid" style="max-height: 500px;">
        </div>
    </div>
    
    <!-- Section Nos valeurs -->
    <div class="mt-5 py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-4">Nos valeurs</h2>
        </div>
        
        <div class="row">
            <!-- Rapidité -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold mb-3">Rapidité</h3>
                            </div>
                        </div>
                        <p class="card-text text-muted">Vos colis livrés en un temps record grâce à nos livreurs disponibles partout.</p>
                    </div>
                </div>
            </div>
            
            <!-- Sécurité -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold mb-3">Sécurité</h3>
                            </div>
                        </div>
                        <p class="card-text text-muted">Un suivi en temps réel et une livraison fiable à chaque étape.</p>
                    </div>
                </div>
            </div>
            
            <!-- Simplicité -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                    <path d="M9 12l2 2 4-4"></path>
                                    <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"></path>
                                    <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"></path>
                                    <path d="M12 3c0 1-1 3-3 3s-3-2-3-3 1-3 3-3 3 2 3 3"></path>
                                    <path d="M12 21c0-1 1-3 3-3s3 2 3 3-1 3-3 3-3-2-3-3"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold mb-3">Simplicité</h3>
                            </div>
                        </div>
                        <p class="card-text text-muted">Expédiez et suivez vos colis en quelques clics depuis l'application.</p>
                    </div>
                </div>
            </div>
            
            <!-- Tarifs justes -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3" style="background-color: #FFD800; border-radius: 15px; padding: 15px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold mb-3">Tarifs justes</h3>
                            </div>
                        </div>
                        <p class="card-text text-muted">Des prix transparents et accessibles, sans frais cachés.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection