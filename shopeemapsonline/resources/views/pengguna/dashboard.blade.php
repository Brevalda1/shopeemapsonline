<!DOCTYPE html>
<html lang="en">
<!-- Previous head content remains exactly the same until the script section -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#EE4D2D">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="SPX Maps">
    <link rel="manifest" href="/manifest.json">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Fullscreen CSS -->
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <style>
        /* All previous styles remain exactly the same */
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
        .navbar {
            padding: 0.8rem 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .navbar-toggler {
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .btn {
            font-weight: 500;
            padding: 0.375rem 1rem;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s;
        }
        
        #installButton {
            display: none;
            margin-right: 10px;
            background-color: white;
            color: #EE4D2D;
            border: 2px solid white;
        }
        
        #installButton:hover {
            background-color: rgba(255,255,255,0.9);
        }
        
        @media (max-width: 991px) {
            .navbar-collapse {
                padding-bottom: 0.5rem;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Previous navbar and content structure remains exactly the same -->
    <nav class="navbar navbar-expand-lg" style="background-color: #EE4D2D;">
        <div class="container">
            <a class="navbar-brand" href="#" style="color: white; font-weight: 600;">
                SPX Maps
            </a>
            
            <!-- Hamburger Button -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarButtons" aria-controls="navbarButtons" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <!-- Collapsible Content -->
            <div class="collapse navbar-collapse" id="navbarButtons">
                <div class="ms-auto d-flex flex-column flex-lg-row gap-2 mt-2 mt-lg-0">
                    <!-- Install Button -->
                    <button type="button" id="installButton" class="btn">
                        <i class="fas fa-download me-2"></i>Install App
                    </button>
                    <!-- New Map Button with named route -->
                    <a href="{{ route('pengguna.dashboard2') }}" class="btn btn-light">
                        <i class="fas fa-map me-2"></i>Lihat Map
                    </a>
                    {{-- <button type="button" id="extend-button" class="btn btn-light">
                        <i class="fas fa-credit-card me-2"></i>Perpanjang Membership
                    </button> --}}
                    <!-- Change Password Button -->
                    <button type="button" class="btn w-100" style="background-color: rgba(255,255,255,0.9); color: #EE4D2D;" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-2"></i>Ganti Password
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn w-100" style="background-color: rgba(255,255,255,0.9); color: #EE4D2D;">Logout</button>
                    </form>
                    <a href="/contact" class="btn btn-light" style="background-color: rgba(255,255,255,0.9); color: #EE4D2D;">
                        <i class="fas fa-map me-2"></i>Terjadi Masalah? hubungi admin
                    </a>
                </div>
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
                            Welcome, {{ session('nama') ?? 'Guest' }}!   Masa berlaku sampai: {{ \Carbon\Carbon::parse(session('tanggal_exp'))->format('d F Y') }}
                        </p>
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

    <!-- Previous scripts remain the same -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    
    <!-- PWA Installation Script remains exactly the same -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registered:', registration);
                    })
                    .catch(error => {
                        console.log('ServiceWorker registration failed:', error);
                    });
            });
        }

        // PWA Installation
        let deferredPrompt;
        const installButton = document.getElementById('installButton');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installButton.style.display = 'block';
        });

        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('User accepted the installation');
                } else {
                    console.log('User dismissed the installation');
                }
                deferredPrompt = null;
                installButton.style.display = 'none';
            }
        });

        window.addEventListener('appinstalled', () => {
            installButton.style.display = 'none';
            deferredPrompt = null;
        });
    </script>

    <!-- Map initialization script with modifications -->
    <script>
        let readonlyMap;
        let userLatLng = null;
        let allMarkers = [];
        let currentFilter = 'semua';
        let watchId = null;
        let userLocationMarker = null;
        let userPulsingMarker = null;
        let proximityLines = [];

        // All previous functions remain exactly the same
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function updateProximityLines(userLat, userLng) {
            proximityLines.forEach(line => readonlyMap.removeLayer(line));
            proximityLines = [];

            allMarkers.forEach(item => {
                if (item.marker && item.coordinates) {
                    const distance = calculateDistance(
                        userLat, 
                        userLng, 
                        item.coordinates[0], 
                        item.coordinates[1]
                    );

                    if (distance <= 2) {
                        const line = L.polyline(
                            [
                                [userLat, userLng],
                                item.coordinates
                            ],
                            {
                                color: '#EE4D2D',
                                weight: 2,
                                opacity: 0.7,
                                dashArray: '5, 10'
                            }
                        ).addTo(readonlyMap);

                        proximityLines.push(line);
                    }
                }
            });
        }

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
                                  startLocationTracking();
                              }
                          });

                return container;
            }
        });

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

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(readonlyMap);

        const locateControl = new L.Control.Locate();
        readonlyMap.addControl(locateControl);

        function updateUserLocationMarker(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            userLatLng = [lat, lng];

            if (userLocationMarker) {
                readonlyMap.removeLayer(userLocationMarker);
            }
            if (userPulsingMarker) {
                readonlyMap.removeLayer(userPulsingMarker);
            }

            userLocationMarker = L.marker(userLatLng, {
                icon: userIcon,
                zIndexOffset: 1000
            }).addTo(readonlyMap)
              .bindPopup("Lokasi Anda");

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

            userPulsingMarker = L.marker(userLatLng, {
                icon: pulsingDot,
                zIndexOffset: 999
            }).addTo(readonlyMap);

            // Always center map on user's location
            readonlyMap.setView(userLatLng, readonlyMap.getZoom(), {
                animate: true,
                duration: 1
            });

            updateProximityLines(lat, lng);
        }

        function handleLocationError(error) {
            console.error("Error getting user location:", error);
            alert("Gagal mendapatkan lokasi: " + error.message);
        }

        function startLocationTracking() {
            if (navigator.geolocation) {
                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                }

                watchId = navigator.geolocation.watchPosition(
                    updateUserLocationMarker,
                    handleLocationError,
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

        function cleanupLocationTracking() {
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }
        }

        window.addEventListener('unload', cleanupLocationTracking);

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

            updateFilterCounters();

            if (userLatLng) {
                updateProximityLines(userLatLng[0], userLatLng[1]);
            }
        }

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

        function addMarker(lat, lng, popupText = "Location") {
            let selectedIcon = pinIcon;
            let category = 'lainnya';
            
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

            allMarkers.push({
                marker: readonlyMarker,
                category: category,
                description: popupText,
                coordinates: [lat, lng]
            });

            if (currentFilter === 'semua' || 
                currentFilter === category || 
                (currentFilter === 'lainnya' && category === 'lainnya')) {
                readonlyMarker.addTo(readonlyMap);
            }

            updateFilterCounters();

            if (userLatLng) {
                updateProximityLines(userLatLng[0], userLatLng[1]);
            }
        }

        function loadPins() {
            fetch("{{ route('pins.index') }}")
                .then(response => response.json())
                .then(data => {
                    allMarkers = [];
                    data.forEach(pin => {
                        addMarker(pin.latitude, pin.longitude, pin.description);
                    });
                    updateFilterCounters();
                    if (userLatLng) {
                        updateProximityLines(userLatLng[0], userLatLng[1]);
                    }
                })
                .catch(error => {
                    console.error("Error loading pins:", error);
                });
        }

        document.querySelectorAll('.btn-filter').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.btn-filter').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                applyFilter(this.dataset.filter);
            });
        });

        loadPins();
        startLocationTracking();
    </script>

    <!-- Midtrans script -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.getElementById('extend-button').onclick = function() {
            const extendButton = this;
            extendButton.disabled = true;
            extendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

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
                        alert('Pembayaran berhasil! Membership Anda telah diperpanjang. silahkan logout lalu login kembali');
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

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('change.password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
