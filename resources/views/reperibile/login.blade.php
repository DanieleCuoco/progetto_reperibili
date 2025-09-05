<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Reperibile</title>
    <link rel="stylesheet" href="{{ asset('reperibile-login.css') }}">
</head>
<body>
    <div class="login-container">
        <h2>Login Area Reperibili</h2>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('reperibile.login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">Accedi</button>
        </form>
        
        <div class="back-link">
            <a href="{{ url('/') }}">Torna alla Home</a>
        </div>
    </div>
</body>
</html>