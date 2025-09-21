<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('login.css') }}" />
</head>
<body>
  <div class="login-container">
    <h1>Accesso Admin</h1>
    <form method="POST" action="{{ route('admin.login') }}" autocomplete="off">
      @csrf
      <label for="email">Username</label>
      <input type="text" id="username" name="username" required autocomplete="off" />
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required autocomplete="off" />
      <button type="submit">Accedi</button>
    </form>

    <a href="/" class="home-button">
      <i class="bi bi-house-fill"></i>
    </a>
    
  </div>
</body>
</html>