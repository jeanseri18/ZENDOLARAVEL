@extends('layouts.app')

@section('title', 'Contact nous - ZENDO')

@section('content')
<div class="container">
    
    <!-- Section Contactez-nous -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold mb-3">Contactez-nous</h2>
            <p class="text-muted">Vous avez une question, besoin d'assistance ou envie d'en savoir plus sur Zendo ?<br>
            Notre équipe est à votre écoute et prête à vous accompagner.</p>
        </div>
    </div>

    <div class="row">
        <!-- Informations de contact -->
        <div class="col-lg-6 mb-4">
            <div class="contact-info">
                <!-- Adresse -->
                <div class="d-flex align-items-center mb-4">
                    <div class="contact-icon me-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#FFD800"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Adresse</h5>
                        <p class="text-muted mb-0">Abidjan, Côte d'Ivoire</p>
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="d-flex align-items-center mb-4">
                    <div class="contact-icon me-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="#FFD800"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Téléphone</h5>
                        <p class="text-muted mb-0">+225 0707070707</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="d-flex align-items-center mb-4">
                    <div class="contact-icon me-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="#FFD800"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Email</h5>
                        <p class="text-muted mb-0">support@zendo.africa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de contact -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Envoyez un message</h4>
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Nom" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Objet" required>
                        </div>
                        <div class="mb-4">
                            <textarea class="form-control" rows="5" placeholder="Message" required></textarea>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #FFD800; border: none; color: #000; font-weight: bold; padding: 12px;">Envoyez</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection