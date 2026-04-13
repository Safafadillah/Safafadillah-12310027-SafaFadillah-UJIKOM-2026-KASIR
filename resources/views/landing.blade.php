<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            overflow-x: hidden;

            scroll-snap-type: y mandatory;
        }

        section {
            scroll-snap-align: start;
        }


        .navbar {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: none;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            -webkit-background-clip: text;
            color: transparent !important;
        }

        .btn-primary-custom {
            background: linear-gradient(105deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 40px;
            padding: 10px 26px;
            font-weight: 600;
            transition: 0.2s;
        }

        .hero-section {
            min-height: 100vh;
            padding: 120px 0 80px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;

            background: url('images/kasir.jpg') no-repeat center center/cover;
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .gradient-text {
            background: linear-gradient(120deg, #2563eb, #3b82f6);
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero-desc {
            font-size: 1.1rem;
            color: #475569;
            max-width: 600px;
        }

        .feature-card {
            background: #fff;
            border-radius: 24px;
            padding: 25px;

            border: 3px solid #dbeafe;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);

            transition: 0.25s;
        }

        .feature-card:hover {
            transform: translateY(-6px) scale(1.02);
            /* sedikit membesar */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            font-size: 2.5rem;
        }

        .full-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        footer {
            background: #0b1120;
            color: white;
            padding: 60px 0 20px;
            scroll-snap-align: start;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
        }

        .footer-link:hover {
            color: white;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Kasir S-App</a>

            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-primary-custom text-white">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </a>
            </div>
        </div>
    </nav>

    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <h1 class="hero-title">
                        Kelola Toko Jadi <br>
                        <span class="gradient-text">Lebih Mudah & Cepat</span>
                    </h1>

                    <p class="hero-desc mt-4">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit perferendis velit illum! Eligendi
                        cumque numquam provident tempore eos odit optio deleniti minima nobis, sed ab ut, praesentium,
                        dolores unde! Maxime?
                    </p>

                    <a href="{{ route('login') }}" class="btn btn-primary-custom text-white mt-3">
                        Mulai Sekarang
                    </a>
                </div>

            </div>
        </div>
    </section>

    <section id="fitur" class="full-section bg-white py-5">
        <div class="container text-center">
            <h3 class="fw-bold mb-5">Kenapa Pilih Kasir S-App</h3>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon text-primary">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h5>Transaksi Cepat</h5>
                        <p class="text-muted">Proses transaksi hanya beberapa detik</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon text-primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5>Manajemen Stok</h5>
                        <p class="text-muted">Update stok otomatis dan realtime</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon text-primary">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <h5>Laporan</h5>
                        <p class="text-muted">Laporan mudah dipahami</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold">Kasir S-App</h5>
                    <p class="text-white-50">
                        Aplikasi kasir sederhana berbasis Laravel untuk membantu bisnis lebih efisien.
                    </p>
                </div>

                <div class="col-md-4">
                    <h6>Menu</h6>
                </div>

                <div class="col-md-4">
                    <h6>Kontak</h6>
                    <p class="text-white-50">Email: kasirsapp@email.com</p>
                    <p class="text-white-50">Telp: (123) 10027</p>
                </div>
            </div>

            <hr class="opacity-25">

            <p class="text-center text-white-50 small">
                © 2026 Kasir S-App - All Rights Reserved
            </p>
        </div>
    </footer>

</body>

</html>
