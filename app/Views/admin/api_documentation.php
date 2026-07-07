<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi Endpoint API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="pagetitle mb-4 text-center">
        <h1 class="fw-bold text-primary">Webservice Server</h1>
        <p class="text-muted">Dokumentasi & Sandbox Pengujian Endpoint API (Poin 6)</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3 text-dark">Spesifikasi RESTful Endpoint</h5>
                    <table class="table table-bordered align-middle" style="font-size: 0.95rem;">
                        <tr class="table-secondary">
                            <th width="35%">HTTP Method</th>
                            <td><span class="badge bg-success px-3 py-2 fs-6">GET</span></td>
                        </tr>
                        <tr>
                            <th>URL Endpoint</th>
                            <td><code class="text-danger fw-bold">/api/bookings</code></td>
                        </tr>
                        <tr>
                            <th>Authentication</th>
                            <td>Header: <code class="bg-light text-dark p-1 rounded border">X-API-KEY</code></td>
                        </tr>
                        <tr>
                            <th>Format Response</th>
                            <td><span class="badge bg-info text-dark">Application/JSON</span></td>
                        </tr>
                    </table>
                
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-dark mb-2">Live API Endpoint Sandbox Tester</h5>
                    <p class="small text-muted mb-4">Simulasikan integrasi Webservice Server dengan menguji hak akses API Key:</p>
                    
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-danger w-50 py-2 fw-bold" onclick="jalankanTesApi(false)">
                            Tanpa API Key
                        </button>
                        <button class="btn btn-success w-50 py-2 fw-bold" onclick="jalankanTesApi(true)">
                            Dengan API Key Valid
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small text-secondary uppercase tracking-wider mb-1">HTTP Response Status:</label>
                        <div id="api-status" class="fs-5 fw-bold text-dark p-2 bg-white rounded border">-</div>
                    </div>

                    <div>
                        <label class="fw-bold small text-secondary uppercase tracking-wider mb-1">Response Data JSON Output:</label>
                        <pre id="api-json-output" class="bg-dark text-warning p-3 rounded font-monospace small mb-0 shadow-inner" style="height: 250px; overflow-y: auto;">Hasil JSON response akan ter-render otomatis di sini...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function jalankanTesApi(pakaiKey) {
    const outputBox = document.getElementById('api-json-output');
    const statusBox = document.getElementById('api-status');
    
    outputBox.textContent = "Sedang memproses request...";
    statusBox.textContent = "Loading...";
    statusBox.className = "fs-5 fw-bold text-secondary p-2 bg-white rounded border";

    const customHeaders = {};
    if (pakaiKey) {
        customHeaders['X-API-KEY'] = 'MUA_SECRET_KEY_2026';
    }

    fetch('/api/bookings', {
        method: 'GET',
        headers: customHeaders
    })
    .then(async response => {
        const dataJson = await response.json();
        
        if (response.status === 200) {
            statusBox.textContent = "200 OK (Akses Diberikan)";
            statusBox.className = "fs-5 fw-bold text-success p-2 bg-light-success rounded border border-success";
        } else if (response.status === 401) {
            statusBox.textContent = "401 Unauthorized (Akses Ditolak)";
            statusBox.className = "fs-5 fw-bold text-danger p-2 bg-light-danger rounded border border-danger";
        } else {
            statusBox.textContent = response.status + " Error";
            statusBox.className = "fs-5 fw-bold text-warning p-2 bg-white rounded border";
        }

        outputBox.textContent = JSON.stringify(dataJson, null, 4);
    })
    .catch(error => {
        statusBox.textContent = "Koneksi Gagal";
        statusBox.className = "fs-5 fw-bold text-danger p-2 bg-white rounded border";
        outputBox.textContent = 'Error Fetching: ' + error;
    });
}
</script>

</body>
</html>