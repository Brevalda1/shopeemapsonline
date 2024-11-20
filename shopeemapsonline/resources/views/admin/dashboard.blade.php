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
    <style>
        .custom-div-icon {
            background: transparent;
            border: none;
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

                        <div id="map" style="height: 500px;" class="mt-3"></div>
                    </div>
                </div>

                <!-- Readonly Map -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Readonly Map</h5>
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
    <script>
        let map, readonlyMap, userMarker;
        const markers = new Map(); // Untuk menyimpan referensi marker
    
        // Initialize the interactive map with temporary default view
        map = L.map('map').setView([-6.200000, 106.816666], 13); // Default to Jakarta
    
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
        });
    
        // Add a tile layer to readonly map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(readonlyMap);
    
        // Custom icons definition with enhanced user location marker
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
    
        // Function to add marker on both maps
        function addMarker(lat, lng, popupText = "Location", id = null) {
            // Pilih icon berdasarkan deskripsi
            let selectedIcon = pinIcon; // default blue icon
            
            const lowerText = popupText.toLowerCase();
            if (lowerText.includes('sembako')) {
                selectedIcon = sembakoIcon; // green icon untuk sembako
            } else if (lowerText.includes('orderantinggi')) {
                selectedIcon = orderanTinggiIcon; // orange icon untuk orderan tinggi
            }
    
            const marker = L.marker([lat, lng], {icon: selectedIcon}).addTo(map);
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
    
            const readonlyMarker = L.marker([lat, lng], {icon: selectedIcon}).addTo(readonlyMap);
            const readonlyPopupContent = `
                <div>
                    ${popupText}
                    <br>
                    <a href="${googleMapsUrl}" target="_blank" class="btn btn-sm btn-primary mt-2 text-white">
                        Navigasi ke Lokasi
                    </a>
                </div>
            `;
            readonlyMarker.bindPopup(readonlyPopupContent);
    
            if (id) {
                markers.set(id, { marker, readonlyMarker });
            }
    
            return marker;
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
                        // Hapus marker lama
                        const markerData = markers.get(id);
                        if (markerData) {
                            map.removeLayer(markerData.marker);
                            readonlyMap.removeLayer(markerData.readonlyMarker);
                            markers.delete(id);
                        }
                        
                        // Tambah marker baru dengan deskripsi yang diupdate
                        addMarker(
                            data.data.latitude,
                            data.data.longitude,
                            newDescription,
                            data.data.id
                        );
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
    
        // Get user location with enhanced marker
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
                            zIndexOffset: 1000 // Ensure user marker is always on top
                        }).addTo(map)
                            .bindPopup("Lokasi Anda")
                            .openPopup();
    
                        L.marker([lat, lng], {
                            icon: userIcon,
                            zIndexOffset: 1000
                        }).addTo(readonlyMap)
                            .bindPopup("Lokasi Anda")
                            .openPopup();
    
                        map.setView([lat, lng], 13);
                        readonlyMap.setView([lat, lng], 13);
    
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
    
        // Add pulsing animation style
        const pulseStyle = document.createElement('style');
        pulseStyle.textContent = `
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
        `;
        document.head.appendChild(pulseStyle);
    
        // Continue with the rest of the code...
    </script>
    <script>
        // Load all pins from the database
        function loadPins() {
            fetch("{{ route('pins.index') }}")
                .then(response => response.json())
                .then(data => {
                    data.forEach(pin => {
                        addMarker(pin.latitude, pin.longitude, pin.description, pin.id);
                    });
                })
                .catch(error => {
                    console.error("Error loading pins:", error);
                });
        }
    
        // Save pin to database with enhanced validation
        function savePinToDatabase(lat, lng, description) {
            // Validate coordinates
            if (!lat || !lng || typeof lat !== 'number' || typeof lng !== 'number') {
                alert("Koordinat tidak valid");
                return;
            }
    
            // Validate description
            if (!description || description.trim().length === 0) {
                alert("Deskripsi tidak boleh kosong");
                return;
            }
    
            fetch("{{ route('pins.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    description: description
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                addMarker(data.latitude, data.longitude, data.description, data.id);
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                successAlert.innerHTML = `
                    Lokasi berhasil ditambahkan
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(successAlert);
                setTimeout(() => successAlert.remove(), 3000);
            })
            .catch(error => {
                console.error("Error saving pin:", error);
                alert("Gagal menyimpan lokasi: " + error.message);
            });
        }
    
        // Delete pin with confirmation and visual feedback
        function deletePin(id) {
            if (confirm("Apakah Anda yakin ingin menghapus lokasi ini?")) {
                fetch(`/pins/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const markerData = markers.get(id);
                        if (markerData) {
                            // Add fade-out animation before removing
                            const { marker, readonlyMarker } = markerData;
                            marker.getElement().style.transition = 'opacity 0.5s';
                            readonlyMarker.getElement().style.transition = 'opacity 0.5s';
                            marker.getElement().style.opacity = '0';
                            readonlyMarker.getElement().style.opacity = '0';
    
                            setTimeout(() => {
                                map.removeLayer(marker);
                                readonlyMap.removeLayer(readonlyMarker);
                                markers.delete(id);
                            }, 500);
    
                            // Show success message
                            const successAlert = document.createElement('div');
                            successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                            successAlert.innerHTML = `
                                Lokasi berhasil dihapus
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            `;
                            document.body.appendChild(successAlert);
                            setTimeout(() => successAlert.remove(), 3000);
                        }
                    } else {
                        alert("Gagal menghapus pin: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error deleting pin:", error);
                    alert("Gagal menghapus pin");
                });
            }
        }
    
        // Process Google Maps link with enhanced validation and feedback
        function processGmapsLink() {
            const link = document.getElementById('gmapsLink').value.trim();
            
            function extractCoordinates(url) {
                try {
                    // Format 1: maps.app.goo.gl
                    if (url.includes('@')) {
                        const match = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                        if (match) {
                            return {
                                lat: parseFloat(match[1]),
                                lng: parseFloat(match[2])
                            };
                        }
                    }
                    
                    // Format 2: Koordinat langsung
                    const coordMatch = url.match(/(-?\d+\.\d+),\s*(-?\d+\.\d+)/);
                    if (coordMatch) {
                        return {
                            lat: parseFloat(coordMatch[1]),
                            lng: parseFloat(coordMatch[2])
                        };
                    }
                    
                    // Format 3: Google Maps share link
                    if (url.includes('maps/place/')) {
                        const match = url.match(/maps\/place\/.*\/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                        if (match) {
                            return {
                                lat: parseFloat(match[1]),
                                lng: parseFloat(match[2])
                            };
                        }
                    }
                    
                    return null;
                } catch (error) {
                    console.error("Error extracting coordinates:", error);
                    return null;
                }
            }
    
            const coordinates = extractCoordinates(link);
            
            if (coordinates && !isNaN(coordinates.lat) && !isNaN(coordinates.lng)) {
                // Validate coordinate ranges
                if (Math.abs(coordinates.lat) > 90 || Math.abs(coordinates.lng) > 180) {
                    alert("Koordinat di luar jangkauan yang valid");
                    return;
                }
    
                const description = prompt("Masukkan deskripsi lokasi:");
                if (description) {
                    savePinToDatabase(coordinates.lat, coordinates.lng, description);
                    map.setView([coordinates.lat, coordinates.lng], 15);
                    document.getElementById('gmapsLink').value = '';
    
                    // Show visual feedback for successful coordinate extraction
                    const input = document.getElementById('gmapsLink');
                    input.style.transition = 'background-color 0.3s';
                    input.style.backgroundColor = '#d4edda';
                    setTimeout(() => {
                        input.style.backgroundColor = '';
                    }, 1000);
                }
            } else {
                alert("Format link Google Maps tidak valid. Pastikan link yang dimasukkan benar.");
                // Show visual feedback for invalid input
                const input = document.getElementById('gmapsLink');
                input.style.transition = 'background-color 0.3s';
                input.style.backgroundColor = '#f8d7da';
                setTimeout(() => {
                    input.style.backgroundColor = '';
                }, 1000);
            }
        }
    
        // Add click event for placing pins with enhanced feedback
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
    
            // Show temporary marker before confirmation
            const tempMarker = L.marker([lat, lng], {
                icon: pinIcon,
                opacity: 0.6
            }).addTo(map);
    
            var popupText = prompt("Masukkan deskripsi lokasi:");
            if (popupText) {
                map.removeLayer(tempMarker);
                savePinToDatabase(lat, lng, popupText);
            } else {
                map.removeLayer(tempMarker);
            }
        });
    
        // Initialize with error handling
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