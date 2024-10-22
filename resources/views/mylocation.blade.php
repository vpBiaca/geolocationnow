<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Realtime Location Tracker</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: 100vh; /* Full height of the viewport */
        }
    </style>
</head>
<body>
    <h1 class="text-center">Track User Location</h1>
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize the map
        var map = L.map('map').setView([14.0860746, 100.608406], 13); // Set initial view

        // OpenStreetMap layer
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });
        osm.addTo(map);

        // Variables for marker and circle
        var marker, circle;

        // Request location
        window.onload = function() {
            if (navigator.geolocation) {
                setInterval(() => {
                    navigator.geolocation.getCurrentPosition(showPosition, handleError);
                }, 5000); // Update every 5 seconds
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Show the user's position
        function showPosition(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;
            var accuracy = position.coords.accuracy;

            // Remove existing marker and circle if present
            if (marker) {
                map.removeLayer(marker);
            }
            if (circle) {
                map.removeLayer(circle);
            }

            // Add new marker and circle to the map
            marker = L.marker([lat, long]).addTo(map);
            circle = L.circle([lat, long], { radius: accuracy }).addTo(map);
            map.setView([lat, long], 13); // Center map on the new position

            // Send location to Laravel backend
            sendLocationToServer(lat, long);
        }

        // Handle errors
        function handleError(error) {
            if (error.code === error.PERMISSION_DENIED) {
                alert("Location access denied. We cannot track your location.");
            }
        }

        // Send location data to the backend
        function sendLocationToServer(lat, long) {
            fetch('/store-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: long
                })
            })
            .then(response => response.json())
            .then(data => console.log('Location stored:', data))
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
