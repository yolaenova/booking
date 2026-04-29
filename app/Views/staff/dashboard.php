<!DOCTYPE html>
<html>
<head>
<title>Staff Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-primary">
<div class="container">
<a class="navbar-brand">Staff Panel</a>
<a href="/logout" class="btn btn-light btn-sm">Logout</a>
</div>
</nav>

<div class="container mt-5">

<h2>Halo, <?= session('name') ?></h2>
<p>Dashboard Makeup Artist</p>

<div class="card shadow mt-4">
<div class="card-body">
<h4>Jadwal Hari Ini</h4>
<p>Belum ada jadwal.</p>
</div>
</div>

</div>

</body>
</html>