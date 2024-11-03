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
        let readonlyMap;

        // Buat custom icon untuk user location (merah)
        const userIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Buat custom icon untuk pins lainnya (biru)
        const pinIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
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
        });

        // Add a tile layer to readonly map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(readonlyMap);

        // Update fungsi addMarker untuk menggunakan pinIcon
        function addMarker(lat, lng, popupText = "Location") {
            const readonlyMarker = L.marker([lat, lng], {icon: pinIcon}).addTo(readonlyMap);
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            const popupContent = `
                <div>
                    ${popupText}<br>
                    <a href="${googleMapsUrl}" target="_blank" class="btn btn-sm btn-primary mt-2 text-white">
                        Navigasi ke Lokasi
                    </a>
                </div>
            `;
            readonlyMarker.bindPopup(popupContent);
        }

        // Update fungsi getUserLocation untuk menggunakan userIcon
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        L.marker([lat, lng], {icon: userIcon}).addTo(readonlyMap)
                            .bindPopup("Your location")
                            .openPopup();

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

        // Load all pins from the database
        function loadPins() {
            fetch("{{ route('pins.index') }}")
                .then(response => response.json())
                .then(data => {
                    data.forEach(pin => {
                        addMarker(pin.latitude, pin.longitude, pin.description);
                    });
                })
                .catch(error => {
                    console.error("Error loading pins:", error);
                });
        }

        // Initialize
        loadPins();
        getUserLocation();
    </script>
</body>
</html>
