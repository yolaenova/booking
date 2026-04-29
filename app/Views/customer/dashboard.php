<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-success">
<div class="container">
<a class="navbar-brand">Customer Panel</a>
<a href="/logout" class="btn btn-light btn-sm">Logout</a>
</div>
</nav>

<div class="container mt-5">

<h2>Halo, <?= session('name') ?></h2>
<p>Selamat datang di Booking Makeup Artist</p>

<div class="card shadow mt-4">
<div class="card-body">
<h4>Booking Makeup Sekarang</h4>
<a href="/services" class="btn btn-success">Lihat Layanan</a>
</div>
</div>

</div>

</body>
</html>