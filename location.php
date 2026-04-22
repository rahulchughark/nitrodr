<!DOCTYPE html>
<html>
<head>
    <title>Location on Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var latitude = <?= $_GET['latitude']; ?>;
        var longitude = <?= $_GET['longitude']; ?>;
        
        var map = L.map('map').setView([latitude, longitude], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        L.marker([latitude, longitude]).addTo(map)
            .bindPopup('Location')
            .openPopup();
    </script>
</body>
</html>

