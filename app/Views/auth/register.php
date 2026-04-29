<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">

<h3 class="text-center mb-4">Register Customer</h3>

<form method="post" action="/register-process">

<input type="text" name="name" class="form-control mb-3" placeholder="Nama Lengkap">

<input type="email" name="email" class="form-control mb-3" placeholder="Email">

<input type="text" name="phone" class="form-control mb-3" placeholder="No HP">

<input type="password" name="password" class="form-control mb-3" placeholder="Password">

<button class="btn btn-success w-100">Register</button>

</form>

<div class="text-center mt-3">
<a href="/login">Kembali Login</a>
</div>

</div>
</div>

</div>
</div>
</div>

</body>
</html>