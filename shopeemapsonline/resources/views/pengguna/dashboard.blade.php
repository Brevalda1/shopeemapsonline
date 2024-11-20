<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Fullscreen CSS -->
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <style>
        .custom-div-icon {
            background: transparent;
            border: none;
        }
        @keyframes pulse {
            0% {
                transform: scale(0.5);
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        .leaflet-control-locate {
            border: 2px solid rgba(0,0,0,0.2);
            background-clip: padding-box;
        }
        .leaflet-control-locate a {
            background-color: #fff;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #000;
        }
        .leaflet-control-locate a:hover {
            background-color: #f4f4f4;
        }
        .filter-buttons {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .filter-buttons .btn {
            margin-right: 10px;
            margin-bottom: 5px;
            min-width: 120px;
        }
        .btn-filter.active {
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.5);
            transform: translateY(1px);
        }
        /* Responsive styling */
        @media (max-width: 768px) {
            .filter-buttons .btn {
                width: calc(50% - 10px);
            }
        }
        @media (max-width: 576px) {
            .filter-buttons .btn {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="ms-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome!</h5>
                        <p class="card-text">
                            Welcome, {{ session('nama') ?? 'Guest' }}!    Welcome, {{ session('tanggal_exp') ?? 'Guest' }}!
                        </p>
                    </div>
                </div>
                <!-- Tambahkan ini di card Welcome -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title">Welcome!</h5>
                <p class="card-text">
                    Welcome, {{ session('nama') ?? 'Guest' }}!<br>
                    Masa berlaku sampai: {{ \Carbon\Carbon::parse(session('tanggal_exp'))->format('d F Y') }}
                </p>
            </div>
            <button type="button" id="extend-button" class="btn btn-success">
                <i class="fas fa-credit-card me-2"></i>Perpanjang Membership
            </button>
        </div>
    </div>
</div>

                <!-- Readonly Map -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Readonly Map</h5>
                        <!-- Filter Buttons -->
                        <div class="filter-buttons">
                            <button class="btn btn-primary btn-filter active" data-filter="semua">
                                <i class="fas fa-globe me-2"></i>Semua
                            </button>
                            <button class="btn btn-warning btn-filter" data-filter="orderantinggi">
                                <i class="fas fa-chart-line me-2"></i>Orderan Tinggi
                            </button>
                            <button class="btn btn-success btn-filter" data-filter="sembako">
                                <i class="fas fa-shopping-basket me-2"></i>Sembako
                            </button>
                            <button class="btn btn-info btn-filter" data-filter="lainnya">
                                <i class="fas fa-list me-2"></i>Lainnya
                            </button>
                        </div>
                        <div id="readonlyMap" style="height: 500px;" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Fullscreen Plugin -->
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <script>
        let readonlyMap;
        let userLatLng = null;
        let allMarkers = []; // Array untuk menyimpan semua marker
        let currentFilter = 'semua'; // Filter default
    
        // Custom user location icon (enhanced)
        const userIcon = L.divIcon({
            html: `
                <div style="
                    background-color: #ff0000; 
                    width: 24px; 
                    height: 24px; 
                    border-radius: 50%; 
                    border: 4px solid white; 
                    box-shadow: 0 0 8px rgba(0,0,0,0.8);
                    position: relative;
                ">
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 10px;
                        height: 10px;
                        background-color: #ff0000;
                        border-radius: 50%;
                        border: 2px solid white;
                        box-shadow: 0 0 4px rgba(0,0,0,0.5);
                    "></div>
                </div>
            `,
            className: 'custom-div-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12],
            popupAnchor: [0, -12]
        });
    
        // Custom icons untuk marker lain
        const pinIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    
        const sembakoIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    
        const orderanTinggiIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    
        // Custom Locate Control
        L.Control.Locate = L.Control.extend({
            options: {
                position: 'topleft'
            },
    
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-control-locate leaflet-bar leaflet-control');
                
                const link = L.DomUtil.create('a', 'leaflet-control-locate-button', container);
                link.href = '#';
                link.title = 'Ke Lokasi Saya';
                link.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="8"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                `;
    
                L.DomEvent.on(link, 'click', L.DomEvent.stopPropagation)
                          .on(link, 'click', L.DomEvent.preventDefault)
                          .on(link, 'click', function() {
                              if (userLatLng) {
                                  map.setView(userLatLng, 15, {
                                      animate: true,
                                      duration: 1
                                  });
                              } else {
                                  getUserLocation();
                              }
                          });
    
                return container;
            }
        });
    
        // Initialize the readonly map
        readonlyMap = L.map('readonlyMap', {
            center: [-6.200000, 106.816666],
            zoom: 13,
            dragging: true,
            zoomControl: true,
            scrollWheelZoom: true,
            doubleClickZoom: false,
            boxZoom: true,
            keyboard: true,
            fullscreenControl: true
        });
    
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(readonlyMap);
    
        // Add locate control
        const locateControl = new L.Control.Locate();
        readonlyMap.addControl(locateControl);
    </script>
    <script>
        // Fungsi untuk menerapkan filter
        function applyFilter(filter) {
            currentFilter = filter;
            
            allMarkers.forEach(item => {
                if (filter === 'semua' || 
                    filter === item.category || 
                    (filter === 'lainnya' && item.category === 'lainnya')) {
                    item.marker.addTo(readonlyMap);
                } else {
                    readonlyMap.removeLayer(item.marker);
                }
            });
    
            // Update counter di tombol filter
            updateFilterCounters();
        }
    
        // Fungsi untuk update counter di tombol filter
        function updateFilterCounters() {
            const counts = {
                semua: allMarkers.length,
                orderantinggi: allMarkers.filter(m => m.category === 'orderantinggi').length,
                sembako: allMarkers.filter(m => m.category === 'sembako').length,
                lainnya: allMarkers.filter(m => m.category === 'lainnya').length
            };
    
            document.querySelectorAll('.btn-filter').forEach(btn => {
                const filter = btn.dataset.filter;
                const count = counts[filter] || 0;
                const badge = btn.querySelector('.badge');
                if (badge) {
                    badge.textContent = count;
                } else {
                    btn.innerHTML += ` <span class="badge bg-white text-dark">${count}</span>`;
                }
            });
        }
    
        // Fungsi addMarker yang dimodifikasi
        function addMarker(lat, lng, popupText = "Location") {
            let selectedIcon = pinIcon; // default blue icon
            let category = 'lainnya'; // default category
            
            const lowerText = popupText.toLowerCase();
            if (lowerText.includes('sembako')) {
                selectedIcon = sembakoIcon;
                category = 'sembako';
            } else if (lowerText.includes('orderantinggi')) {
                selectedIcon = orderanTinggiIcon;
                category = 'orderantinggi';
            }
    
            const readonlyMarker = L.marker([lat, lng], {icon: selectedIcon});
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            const popupContent = `
                <div>
                    ${popupText}<br>
                    <div class="mt-2">
                        <a href="${googleMapsUrl}" target="_blank" class="btn btn-sm btn-primary text-white">
                            Navigasi ke Lokasi
                        </a>
                    </div>
                </div>
            `;
            readonlyMarker.bindPopup(popupContent);
    
            // Tambahkan marker ke array
            allMarkers.push({
                marker: readonlyMarker,
                category: category,
                description: popupText,
                coordinates: [lat, lng]
            });
    
            // Terapkan filter saat ini
            if (currentFilter === 'semua' || 
                currentFilter === category || 
                (currentFilter === 'lainnya' && category === 'lainnya')) {
                readonlyMarker.addTo(readonlyMap);
            }
    
            // Update counter
            updateFilterCounters();
        }
    
        // Get user location dengan penyimpanan koordinat
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        userLatLng = [lat, lng];
    
                        // Hapus marker user yang lama jika ada
                        if (window.userLocationMarker) {
                            readonlyMap.removeLayer(window.userLocationMarker);
                        }
                        if (window.userPulsingMarker) {
                            readonlyMap.removeLayer(window.userPulsingMarker);
                        }
    
                        // Add user location marker
                        window.userLocationMarker = L.marker(userLatLng, {
                            icon: userIcon,
                            zIndexOffset: 1000
                        }).addTo(readonlyMap)
                            .bindPopup("Lokasi Anda")
                            .openPopup();
    
                        // Add pulsing effect
                        const pulsingDot = L.divIcon({
                            html: `
                                <div style="
                                    animation: pulse 1.5s infinite;
                                    background-color: rgba(255, 0, 0, 0.3);
                                    border-radius: 50%;
                                    height: 40px;
                                    width: 40px;
                                    position: relative;
                                "></div>
                            `,
                            className: 'custom-div-icon',
                            iconSize: [40, 40],
                            iconAnchor: [20, 20]
                        });
    
                        window.userPulsingMarker = L.marker(userLatLng, {
                            icon: pulsingDot,
                            zIndexOffset: 999
                        }).addTo(readonlyMap);
    
                        readonlyMap.setView(userLatLng, 15);
                    },
                    function(error) {
                        console.error("Error getting user location:", error);
                        alert("Gagal mendapatkan lokasi: " + error.message);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        }
    
        // Load pins from database
        function loadPins() {
            fetch("{{ route('pins.index') }}")
                .then(response => response.json())
                .then(data => {
                    allMarkers = []; // Reset markers
                    data.forEach(pin => {
                        addMarker(pin.latitude, pin.longitude, pin.description);
                    });
                    updateFilterCounters(); // Update counters setelah load
                })
                .catch(error => {
                    console.error("Error loading pins:", error);
                });
        }
    
        // Event listener untuk tombol filter
        document.querySelectorAll('.btn-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Update tampilan tombol aktif
                document.querySelectorAll('.btn-filter').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
    
                // Terapkan filter
                applyFilter(this.dataset.filter);
            });
        });
    
        // Initialize
        loadPins();
        getUserLocation();
    </script>
    
<!-- Tambahkan script ini di bagian bawah sebelum </body> -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('extend-button').onclick = function() {
    // Tampilkan loading
    const extendButton = this;
    extendButton.disabled = true;
    extendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    // Kirim request untuk mendapatkan token
    fetch('{{ route("membership.payment.token") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            resetButton();
            return;
        }

        snap.pay(data.snap_token, {
            onSuccess: function(result) {
                handlePaymentSuccess(result);
            },
            onPending: function(result) {
                alert('Pembayaran pending, silakan selesaikan pembayaran');
                resetButton();
            },
            onError: function(result) {
                alert('Pembayaran gagal');
                resetButton();
            },
            onClose: function() {
                resetButton();
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran');
        resetButton();
    });

    function resetButton() {
        extendButton.disabled = false;
        extendButton.innerHTML = '<i class="fas fa-credit-card me-2"></i>Perpanjang Membership';
    }

    function handlePaymentSuccess(result) {
        fetch('{{ route("membership.payment.success") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(result)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pembayaran berhasil! Membership Anda telah diperpanjang.');
                window.location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan');
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran');
            resetButton();
        });
    }
};
</script>
    </body>
    </html>