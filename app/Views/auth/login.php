<!DOCTYPE html>
<html>
<head>
<title>Login MUA Booking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-4">

<div class="card shadow">
<div class="card-body">

<h3 class="text-center mb-4">Login</h3>

<form method="post" action="/login-process">

<input type="email" name="email" class="form-control mb-3" placeholder="Email">

<input type="password" name="password" class="form-control mb-3" placeholder="Password">

<button class="btn btn-primary w-100">Login</button>

</form>

<div class="text-center mt-3">
<a href="/register">Register Customer</a>
</div>

</div>
</div>

</div>
</div>
</div>

</body>
</html>