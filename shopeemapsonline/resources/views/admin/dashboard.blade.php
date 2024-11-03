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

        // Buat custom icon untuk user location
        const userIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Buat custom icon untuk pins lainnya
        const pinIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Function to add marker on both maps
        function addMarker(lat, lng, popupText = "Location", id = null) {
            const marker = L.marker([lat, lng], {icon: pinIcon}).addTo(map);
            const popupContent = `
                <div>${popupText}</div>
                ${id ? `<button class="btn btn-danger btn-sm mt-2" onclick="deletePin(${id}, this)">Delete Location</button>` : ""}
            `;
            marker.bindPopup(popupContent);

            const readonlyMarker = L.marker([lat, lng], {icon: pinIcon}).addTo(readonlyMap);
            readonlyMarker.bindPopup(`<div>${popupText}</div>`);
        }

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

        // Update fungsi getUserLocation untuk menggunakan userIcon
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        userMarker = L.marker([lat, lng], {icon: userIcon}).addTo(map)
                            .bindPopup("Your location")
                            .openPopup();

                        L.marker([lat, lng], {icon: userIcon}).addTo(readonlyMap)
                            .bindPopup("Your location")
                            .openPopup();

                        map.setView([lat, lng], 13);
                        readonlyMap.setView([lat, lng], 13);
                    },
                    function(error) {
                        console.error("Error getting user location:", error);
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Save pin to database
        function savePinToDatabase(lat, lng, description) {
            fetch("{{ route('pins.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                addMarker(data.latitude, data.longitude, data.description, data.id);
            })
            .catch(error => {
                console.error("Error saving pin:", error);
            });
        }

        // Delete pin
        function deletePin(id) {
            if (confirm("Are you sure you want to delete this pin?")) {
                fetch(`/pins/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    location.reload();
                })
                .catch(error => {
                    console.error("Error deleting pin:", error);
                });
            }
        }

        // Add click event for placing pins
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            var popupText = prompt("Enter location description:");
            if (popupText) {
                savePinToDatabase(lat, lng, popupText);
            }
        });

        // Initialize
        loadPins();
        getUserLocation();

        function processGmapsLink() {
            const link = document.getElementById('gmapsLink').value.trim();
            
            // Fungsi untuk mengekstrak koordinat dari link
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
                    
                    return null;
                } catch (error) {
                    console.error("Error extracting coordinates:", error);
                    return null;
                }
            }

            const coordinates = extractCoordinates(link);
            
            if (coordinates && !isNaN(coordinates.lat) && !isNaN(coordinates.lng)) {
                const description = prompt("Masukkan deskripsi lokasi:");
                if (description) {
                    savePinToDatabase(coordinates.lat, coordinates.lng, description);
                    map.setView([coordinates.lat, coordinates.lng], 15);
                    document.getElementById('gmapsLink').value = ''; // Clear input
                }
            } else {
                alert("Format link Google Maps tidak valid. Pastikan link yang dimasukkan benar.");
            }
        }
    </script>
</body>
</html>
