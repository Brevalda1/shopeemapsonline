<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopee Maps Online</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1596443686812-2f45229eebc3?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
        }
        
        .feature-box {
            padding: 30px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 40px;
            color: #ee4d2d;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://via.placeholder.com/40" alt="Logo" class="me-2">
                Shopee Maps Online
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
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-light" href="/login">Masuk</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary" href="/register">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Selamat Datang di Shopee Maps Online</h1>
            <p class="lead mb-4">Platform terpercaya untuk manajemen pengiriman dan tracking barang Shopee Anda</p>
            <a href="/register" class="btn btn-primary btn-lg me-3">Mulai Sekarang</a>
            <a href="#fitur" class="btn btn-outline-light btn-lg">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="fitur">
        <div class="container">
            <h2 class="text-center mb-5">Fitur Unggulan Kami</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box shadow-sm rounded">
                        <i class="fas fa-map-marked-alt feature-icon"></i>
                        <h4>Tracking Real-time</h4>
                        <p>Pantau lokasi pengiriman Anda secara real-time dengan akurasi tinggi</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box shadow-sm rounded">
                        <i class="fas fa-chart-line feature-icon"></i>
                        <h4>Analisis Data</h4>
                        <p>Dapatkan insight bermakna dari data pengiriman Anda</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box shadow-sm rounded">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <h4>Keamanan Terjamin</h4>
                        <p>Sistem keamanan tingkat tinggi untuk melindungi data Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="bg-light py-5" id="tentang">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-4">Tentang Kami</h2>
                    <p class="lead">Shopee Maps Online adalah platform inovatif yang menghubungkan penjual Shopee dengan layanan tracking pengiriman yang handal.</p>
                    <p>Kami berkomitmen untuk memberikan pengalaman terbaik dalam manajemen pengiriman barang Anda.</p>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/500x300" alt="About Us" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5" id="kontak">
        <div class="container">
            <h2 class="text-center mb-5">Hubungi Kami</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pesan</label>
                                    <textarea class="form-control" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Shopee Maps Online</h5>
                    <p>Platform tracking pengiriman terpercaya untuk penjual Shopee</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Ikuti Kami</h5>
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small>&copy; 2024 Shopee Maps Online. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
