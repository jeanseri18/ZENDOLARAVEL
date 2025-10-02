<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        
        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            padding: 2rem;
        }
        
        .background-section {
            flex: 1;
            background-color: #FFD800;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .logo {
            width: 200px;
            height: auto;
            margin-bottom: 2rem;
        }
        
        .delivery-image {
            width: 500px;
            /* height: auto; */
            max-width: 100%;
        }
        
        .login-form {
            width: 100%;
            max-width: 400px;
        }
        
        .form-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: bold;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FFD800;
        }
        
        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #FFD800;
            color: black;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-btn:hover {
            background-color: #e6c200;
        }
        
        .back-button-container {
            text-align: center;
            margin-top: 1rem;
        }
        
        .back-btn {
            background-color: black;
            color: white;
           width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            cursor: pointer;

            transition: background-color 0.3s;
        }
        
        .back-btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-section">
            <form class="login-form" method="POST" action="{{ route('login.submit') }}">
                @csrf
                <h1 class="form-title">Connexion</h1>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                
                <button type="submit" class="login-btn">Se connecter</button>
                
                <div class="back-button-container">
                    <a href="{{ route('welcome') }}" class="back-btn">Retour</a>
                </div>
            </form>
        </div>
        
        <div class="background-section">
            <!-- Logo ZENDO -->
            <!-- <svg class="logo" viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg">
                <text x="10" y="35" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="black">ZENDO</text>
                <rect x="0" y="10" width="30" height="20" fill="none" stroke="black" stroke-width="2"/>
                <rect x="5" y="15" width="20" height="10" fill="none" stroke="black" stroke-width="1"/>
                <line x1="0" y1="5" x2="10" y2="5" stroke="black" stroke-width="2"/>
                <line x1="0" y1="0" x2="15" y2="0" stroke="black" stroke-width="2"/>
                <line x1="0" y1="-5" x2="20" y2="-5" stroke="black" stroke-width="2" transform="translate(0,10)"/>
            </svg> -->
            
            <!-- Image du livreur -->
            <img src="{{ asset('adminpic/logo.png') }}" alt="Livreur ZENDO" >
            <img src="{{ asset('adminpic/ppppp 1.png') }}" alt="Livreur ZENDO" style="width: 90%;" >
        </div>
    </div>
</body>
</html>