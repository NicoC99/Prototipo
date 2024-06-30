<?php
$this->title = 'Mapa de Ubicación del Vehículo';
?>

<h1><center><?= $this->title ?></center></h1>

<div id="map" style="height: 500px; width: 100%;"></div>

<script>
function initMap() {
    // Coordenadas aleatorias dentro de un rango
    var lat = Math.random() * (85 - (-85)) + (-85);  // Rango de latitudes: -85 a 85
    var lng = Math.random() * (180 - (-180)) + (-180);  // Rango de longitudes: -180 a 180

    var location = { lat: lat, lng: lng };

    var map = new google.maps.Map(document.getElementById('map'), {
        center: location,
        zoom: 4
    });

    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
}
</script>

<!-- Incluir la API de Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuzTz_zfLfWk167kt0-xRPrI9Zyq5uftQ&callback=initMap" async defer></script>