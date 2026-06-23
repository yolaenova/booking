<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Komponen 5: Webservice Client</h4>
                    </div>
                    <div class="card-body text-center py-4">
                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-warning"><?= session()->getFlashdata('error'); ?></div>
                        <?php endif; ?>
                        
                        <h5 class="text-muted">Mata Uang Global Terkini</h5>
                        <h1 class="display-4 fw-bold text-success my-3">1 USD = Rp <?= number_format($kurs_usd, 0, ',', '.'); ?></h1>
                        <span class="badge bg-info text-dark">Data Di-cache Otomatis (1 Jam)</span>
                    </div>
                    <div class="card-footer text-end">
                        <a href="<?= base_url('admin/dashboard'); ?>" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>