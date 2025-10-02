@extends('layouts.app')

@section('title', 'FAQ - ZENDO')

@section('content')
<div class="container">
    <h1 class="page-title text-center mb-5">Foire Aux Questions (FAQ)</h1>
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Question 1 -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background-color: #FFD800; border: none;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-question-circle me-2"></i>
                        1. Comment fonctionne Zendo ?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Zendo est une application de livraison qui met en relation des expéditeurs et des coursiers. 
                        Vous téléchargez l'application, créez un compte, puis vous pouvez envoyer ou livrer des colis 
                        en toute simplicité.
                    </p>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background-color: #FFD800; border: none;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-question-circle me-2"></i>
                        2. Qui peut devenir coursier ?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Toute personne disposant d'un moyen de transport (moto, voiture, vélo, etc.) 
                        et d'un smartphone peut s'inscrire comme coursier sur Zendo. 
                        L'inscription est simple et validée rapidement.
                    </p>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background-color: #FFD800; border: none;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-question-circle me-2"></i>
                        3. Est-ce que mes colis sont en sécurité ?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Oui. Tous les coursiers et expéditeurs sont vérifiés et vos paiements 
                        sont sécurisés. De plus, vous pouvez suivre vos envois en temps 
                        réel via l'application.
                    </p>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background-color: #FFD800; border: none;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-question-circle me-2"></i>
                        4. Quels sont les moyens de paiement disponibles ?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Vous pouvez payer vos livraisons directement dans l'application 
                        par mobile money, carte bancaire ou autres moyens sécurisés 
                        disponibles dans votre région.
                    </p>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background-color: #FFD800; border: none;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-question-circle me-2"></i>
                        5. Puis-je livrer ou expédier partout ?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Oui ! Zendo est disponible dans toute la Côte d'Ivoire 
                        et s'étend progressivement en Afrique et à l'international.
                    </p>
                </div>
            </div>

            <!-- Question 6 -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background-color: #FFD800; border: none;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-question-circle me-2"></i>
                        6. Quels sont les frais de livraison ?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Les tarifs sont calculés de façon transparente selon la distance, 
                        la taille et le poids du colis. Vous pouvez obtenir une estimation 
                        directement dans l'application avant de confirmer l'envoi.
                    </p>
                </div>
            </div>

            <!-- Section Contact -->
            <div class="text-center mt-5 p-4" style="background-color: #f8f9fa; border-radius: 10px;">
                <h4 class="fw-bold mb-3">Vous avez d'autres questions ?</h4>
                <p class="text-muted mb-3">Notre équipe est là pour vous aider !</p>
                <a href="{{ route('contact') }}" class="btn btn-lg px-4" style="background-color: #FFD800; color: black; font-weight: bold; border: none;">Contactez-nous</a>
            </div>
        </div>
    </div>
</div>
@endsection