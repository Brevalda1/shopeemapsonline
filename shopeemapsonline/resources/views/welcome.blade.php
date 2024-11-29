<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPX Maps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --shopee-orange: #ee4d2d;
            --shopee-light-orange: #ff6742;
            --shopee-dark-orange: #d23f25;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .navbar {
            background-color: var(--shopee-orange) !important;
        }

        .hero-section {
            position: relative;
            background-color: #2c2c2c;
            color: white;
            padding: 100px 0;
            margin-top: 56px;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--shopee-orange) 0%, var(--shopee-dark-orange) 100%);
            opacity: 0.9;
            z-index: 1;
        }

        .hero-section .container {
            position: relative;
            z-index: 2;
        }

        .btn-primary {
            background-color: var(--shopee-orange);
            border-color: var(--shopee-orange);
        }

        .btn-primary:hover {
            background-color: var(--shopee-light-orange);
            border-color: var(--shopee-light-orange);
        }

        .feature-box {
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
            margin-bottom: 20px;
            height: 100%;
            border: 1px solid #eee;
            background: white;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 40px;
            color: var(--shopee-orange);
            margin-bottom: 20px;
        }

        .about-illustration {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }

        .about-illustration i {
            font-size: 120px;
            color: var(--shopee-orange);
            opacity: 0.8;
        }

        .feature-list-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .feature-list-item:hover {
            background-color: rgba(238, 77, 45, 0.1);
        }

        .feature-list-item i {
            color: var(--shopee-orange);
            margin-right: 10px;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }

            .feature-box {
                margin-bottom: 1rem;
            }

            .about-illustration {
                margin-top: 2rem;
            }
        }

        footer {
            background-color: #2c2c2c;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.1);
            transition: background-color 0.3s;
            margin: 0 5px;
        }

        .social-icons a:hover {
            background-color: var(--shopee-orange);
            color: white !important;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-map-marked-alt me-2"></i>
                SPX Maps
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-light" href="/login">Masuk</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-dark" href="/register">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Tingkatkan Penghasilan SPX Anda</h1>
            <p class="lead mb-4">Platform khusus untuk driver Shopee Food yang ingin mendapatkan orderan SPX dengan lebih mudah dan efisien</p>
            <a href="/register" class="btn btn-primary btn-lg me-2 mb-2">Mulai Sekarang</a>
            <a href="#fitur" class="btn btn-outline-light btn-lg mb-2">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="fitur">
        <div class="container">
            <h2 class="text-center mb-5">Fitur Unggulan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box shadow-sm rounded h-100">
                        <i class="fas fa-map-marker-alt feature-icon"></i>
                        <h4>Peta SPX Real-time</h4>
                        <p>Temukan lokasi pickup SPX terdekat dengan posisi Anda secara real-time</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box shadow-sm rounded h-100">
                        <i class="fas fa-route feature-icon"></i>
                        <h4>Rute Optimal</h4>
                        <p>Dapatkan saran rute terbaik untuk mengambil multiple pickup SPX</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box shadow-sm rounded h-100">
                        <i class="fas fa-bell feature-icon"></i>
                        <h4>Notifikasi Pickup</h4>
                        <p>Terima notifikasi real-time untuk pickup SPX di area Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="bg-light py-5" id="tentang">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4">Mengapa SPX Maps?</h2>
                    <p class="lead">Maksimalkan penghasilan Anda sebagai driver Shopee Food dengan mengambil orderan SPX di sela waktu Anda.</p>
                    <div class="feature-list">
                        <div class="feature-list-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Temukan pickup point SPX terdekat</span>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-gas-pump"></i>
                            <span>Hemat waktu dan bahan bakar</span>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Tingkatkan efisiensi pengiriman</span>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-clock"></i>
                            <span>Kelola jadwal lebih fleksibel</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-illustration">
                        <i class="fas fa-motorcycle"></i>
                        <h4 class="mt-3">Siap Mengantarkan</h4>
                        <p>Bergabunglah dengan ribuan driver yang telah meningkatkan penghasilannya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-map-marked-alt me-2"></i>Shopee SPX Maps</h5>
                    <p>Platform pintar untuk driver Shopee Food dalam menemukan orderan SPX</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="mb-3">Ikuti Kami</h5>
                    <div class="social-icons">
                        <a href="#" class="text-light"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small>&copy; 2024 SPX Maps. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>