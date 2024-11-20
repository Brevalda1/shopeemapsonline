<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="ms-auto d-flex">
                <form action="{{ route('logout') }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
                <form action="{{ route('pengguna.index') }}" method="GET">
                    <button type="submit" class="btn btn-primary">lihat users</button>
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
                        <p class="card-text">You're logged in!</p>
                    </div>
                </div>

                <!-- Interactive Map -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Interactive Map</h5>
                        
                        <!-- Form input Google Maps link -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" id="gmapsLink" class="form-control" placeholder="Masukkan link Google Maps...">
                                <button class="btn btn-primary" onclick="processGmapsLink()">Tambah Lokasi</button>
                            </div>
                            <small class="text-muted">Contoh: -7.368250282874654, 112.6955156434918</small>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="filter-buttons">
                            <button class="btn btn-primary btn-filter active" data-filter="semua">
                                Semua <span class="badge bg-white text-dark">0</span>
                            </button>
                            <button class="btn btn-warning btn-filter" data-filter="orderantinggi">
                                Orderan Tinggi <span class="badge bg-white text-dark">0</span>
                            </button>
                            <button class="btn btn-success btn-filter" data-filter="sembako">
                                Sembako <span class="badge bg-white text-dark">0</span>
                            </button>
                            <button class="btn btn-info btn-filter" data-filter="lainnya">
                                Lainnya <span class="badge bg-white text-dark">0</span>
                            </button>
                        </div>

                        <div id="map" style="height: 500px;" class="mt-3"></div>
                    </div>
                </div>

                <!-- Readonly Map -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Readonly Map</h5>
                        <!-- Filter Buttons for Readonly Map -->
                        <div class="filter-buttons">
                            <button class="btn btn-primary btn-filter-readonly active" data-filter="semua">
                                Semua <span class="badge bg-white text-dark">0</span>
                            </button>
                            <button class="btn btn-warning btn-filter-readonly" data-filter="orderantinggi">
                                Orderan Tinggi <span class="badge bg-white text-dark">0</span>
                            </button>
                            <button class="btn btn-success btn-filter-readonly" data-filter="sembako">
                                Sembako <span class="badge bg-white text-dark">0</span>
                            </button>
                            <button class="btn btn-info btn-filter-readonly" data-filter="lainnya">
                                Lainnya <span class="badge bg-white text-dark">0</span>
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
        let map, readonlyMap, userMarker;
        let allMarkers = []; // Array untuk menyimpan semua marker
        let currentFilter = 'semua'; // Filter default
        const markers = new Map(); // Untuk menyimpan referensi marker
    
        // Initialize the interactive map with temporary default view
        map = L.map('map', {
            center: [-6.200000, 106.816666],
            zoom: 13,
            fullscreenControl: true
        });
    
        // Add a tile layer to interactive map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    
        // Initialize the readonly map with zoom and drag enabled
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
    
        // Add a tile layer to readonly map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(readonlyMap);
    
        // Custom icons definition
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
    
        // Fungsi untuk menerapkan filter
        function applyFilter(filter) {
            currentFilter = filter;
            
            allMarkers.forEach(item => {
                const markerData = markers.get(item.id);
                if (markerData) {
                    if (filter === 'semua' || 
                        filter === item.category || 
                        (filter === 'lainnya' && item.category === 'lainnya')) {
                        map.addLayer(markerData.marker);
                        readonlyMap.addLayer(markerData.readonlyMarker);
                    } else {
                        map.removeLayer(markerData.marker);
                        readonlyMap.removeLayer(markerData.readonlyMarker);
                    }
                }
            });
    
            updateFilterCounters();
        }
    
        // Fungsi untuk update counter
        function updateFilterCounters() {
            const counts = {
                semua: allMarkers.length,
                orderantinggi: allMarkers.filter(m => m.category === 'orderantinggi').length,
                sembako: allMarkers.filter(m => m.category === 'sembako').length,
                lainnya: allMarkers.filter(m => m.category === 'lainnya').length
            };
    
            // Update untuk interactive map
            document.querySelectorAll('.btn-filter').forEach(btn => {
                const filter = btn.dataset.filter;
                const badge = btn.querySelector('.badge');
                if (badge) {
                    badge.textContent = counts[filter] || 0;
                }
            });
    
            // Update untuk readonly map
            document.querySelectorAll('.btn-filter-readonly').forEach(btn => {
                const filter = btn.dataset.filter;
                const badge = btn.querySelector('.badge');
                if (badge) {
                    badge.textContent = counts[filter] || 0;
                }
            });
        }
    </script>
    <script>
        // Function to add marker on both maps
        function addMarker(lat, lng, popupText = "Location", id = null) {
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
    
            const marker = L.marker([lat, lng], {icon: selectedIcon});
            const readonlyMarker = L.marker([lat, lng], {icon: selectedIcon});
    
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            const popupContent = `
                <div>
                    ${popupText}
                    <br>
                    <div class="d-flex gap-2 mt-2">
                        <a href="${googleMapsUrl}" target="_blank" class="btn btn-sm btn-primary text-white">
                            Navigasi ke Lokasi
                        </a>
                        ${id ? `
                            <button class="btn btn-warning btn-sm" onclick="editPin(${id}, '${popupText}', this)">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deletePin(${id}, this)">
                                Delete
                            </button>
                        ` : ""}
                    </div>
                </div>
            `;
            marker.bindPopup(popupContent);
    
            const readonlyPopupContent = `
                <div>
                    ${popupText}<br>
                    <a href="${googleMapsUrl}" target="_blank" class="btn btn-sm btn-primary mt-2 text-white">
                        Navigasi ke Lokasi
                    </a>
                </div>
            `;
            readonlyMarker.bindPopup(readonlyPopupContent);
    
            if (id) {
                markers.set(id, { marker, readonlyMarker });
                allMarkers.push({
                    id: id,
                    category: category,
                    coordinates: [lat, lng],
                    description: popupText
                });
            }
    
            if (currentFilter === 'semua' || 
                currentFilter === category || 
                (currentFilter === 'lainnya' && category === 'lainnya')) {
                marker.addTo(map);
                readonlyMarker.addTo(readonlyMap);
            }
    
            updateFilterCounters();
            return marker;
        }
    
        // Get user location
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
    
                        // Remove existing user marker if exists
                        if (userMarker) {
                            map.removeLayer(userMarker);
                        }
    
                        userMarker = L.marker([lat, lng], {
                            icon: userIcon,
                            zIndexOffset: 1000
                        }).addTo(map)
                            .bindPopup("Lokasi Anda")
                            .openPopup();
    
                        L.marker([lat, lng], {
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
    
                        L.marker([lat, lng], {
                            icon: pulsingDot,
                            zIndexOffset: 999
                        }).addTo(map);
    
                        L.marker([lat, lng], {
                            icon: pulsingDot,
                            zIndexOffset: 999
                        }).addTo(readonlyMap);
    
                        map.setView([lat, lng], 13);
                        readonlyMap.setView([lat, lng], 13);
                    },
                    function(error) {
                        console.error("Error getting user location:", error);
                        alert("Gagal mendapatkan lokasi: " + error.message);
                    }
                );
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        }
    
        // Edit pin function
        function editPin(id, currentDescription) {
            const newDescription = prompt("Edit deskripsi lokasi:", currentDescription);
            
            if (newDescription && newDescription !== currentDescription) {
                fetch(`/pins/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        description: newDescription
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove old marker
                        const markerData = markers.get(id);
                        if (markerData) {
                            map.removeLayer(markerData.marker);
                            readonlyMap.removeLayer(markerData.readonlyMarker);
                            markers.delete(id);
                        }
                        
                        // Remove from allMarkers array
                        const index = allMarkers.findIndex(m => m.id === id);
                        if (index > -1) {
                            allMarkers.splice(index, 1);
                        }
                        
                        // Add new marker
                        addMarker(
                            data.data.latitude,
                            data.data.longitude,
                            newDescription,
                            data.data.id
                        );
    
                        updateFilterCounters();
                    } else {
                        alert("Gagal mengupdate pin: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error updating pin:", error);
                    alert("Gagal mengupdate pin");
                });
            }
        }
    
        // Event listeners for filter buttons
        document.querySelectorAll('.btn-filter, .btn-filter-readonly').forEach(button => {
            button.addEventListener('click', function() {
                const filterType = this.classList.contains('btn-filter') ? '.btn-filter' : '.btn-filter-readonly';
                
                // Update active state
                document.querySelectorAll(filterType).forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
    
                // Apply filter
                applyFilter(this.dataset.filter);
            });
        });
    
        // Load pins from database
        function loadPins() {
            fetch("{{ route('pins.index') }}")
                .then(response => response.json())
                .then(data => {
                    allMarkers = []; // Reset markers
                    data.forEach(pin => {
                        addMarker(pin.latitude, pin.longitude, pin.description, pin.id);
                    });
                    updateFilterCounters();
                })
                .catch(error => {
                    console.error("Error loading pins:", error);
                });
        }
    
        // Initialize everything
        try {
            loadPins();
            getUserLocation();
        } catch (error) {
            console.error("Error during initialization:", error);
            alert("Terjadi kesalahan saat memuat peta. Silakan refresh halaman.");
        }
    </script>
    </body>
    </html>