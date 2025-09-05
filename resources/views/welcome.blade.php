<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Welcome Page</title>
<link rel="stylesheet" href="welcome.css" />
</head>
<body>
  <h1>Benvenuto nell'app per i reperibili</h1>
<button class="btn1" onclick="location.href='{{ route('admin.login') }}'">Area Admin</button>
  <button class="btn2" onclick="location.href='{{ route('reperibile.login') }}'">Area Reperibili</button>
  <button class="btn3" onclick="location.href='{{ route('users.calendar') }}'">Area Users</button></body>
</html>