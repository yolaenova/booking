<!DOCTYPE html>
<html>
<head>
    <title>Login MUA Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            background: #ffffff;
            overflow: hidden;
        }
        .brand-header {
            background: linear-gradient(135deg, #581c24 0%, #300c12 100%);
            color: #ffffff;
            padding: 35px 20px;
            text-align: center;
        }
        .brand-header h3 {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-size: 1.6rem;
        }
        .brand-header p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin: 0;
        }
        .form-control-pill {
            border-radius: 0 50px 50px 0;
            padding: 10px 15px;
            border: 1px solid #ced4da;
        }
        .form-control-pill:focus {
            border-color: #581c24;
            box-shadow: 0 0 0 0.25rem rgba(88, 28, 36, 0.15);
        }
        .input-group-text-custom {
            background-color: #white;
            border-radius: 50px 0 0 50px;
            border-right: none;
            background: #fff;
            color: #6c757d;
            padding-left: 20px;
        }
        .btn-login {
            background: linear-gradient(135deg, #581c24 0%, #421218 100%);
            border: none;
            border-radius: 50px;
            padding: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #ffffff;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #421218 0%, #29080d 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(88, 28, 36, 0.3);
        }
        .register-link {
            color: #581c24;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .register-link:hover {
            color: #a93242;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="card shadow login-card">
                
                <div class="brand-header">
                    <h3><i class="bi bi-gem me-2"></i>MUA Booking</h3>
                    <p>Silakan masuk untuk mengakses layanan</p>
                </div>

                <div class="card-body p-4">

                    <form method="post" action="/login-process">

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control form-control-pill" placeholder="Email" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control form-control-pill" placeholder="Password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-2">
                            Login <i class="bi bi-arrow-right-short ms-1"></i>
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <p class="small text-muted mb-0">Belum punya akun? 
                            <a href="/register" class="register-link">Register Customer</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>