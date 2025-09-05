<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Welcome Page</title>
<link rel="stylesheet" href="welcome.css" />
<link rel="stylesheet" href="animation.css" />
</head>
<body>
  <h1>Benvenuto nell'app per i reperibili</h1>
  <button class="btn1 hidden-left" onclick="location.href='{{ route('admin.login') }}'">Area Admin</button>
  <button class="btn2 hidden" onclick="location.href='{{ route('reperibile.login') }}'">Area Reperibili</button>
  <button class="btn3 hidden-right" onclick="location.href='{{ route('users.calendar') }}'">Area Users</button>
  
  <script src="animation.js"></script>
</body>
</html>