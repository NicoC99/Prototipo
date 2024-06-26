<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $id string */

$this->title = 'Compartir Ubicación';
?>

<div class="site-view-location">
    <h1><?= $this->title ?></h1>
    <p>Haz clic en el siguiente botón para compartir tu ubicación en tiempo real.</p>
    <button id="share-location" class="btn btn-primary">Compartir Ubicación</button>
</div>

<script>
document.getElementById('share-location').onclick = function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const id = '<?= $id ?>';

            // Guardar la ubicación en el servidor
            fetch('<?= Url::to(['track/save-location'], true) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>'
                },
                body: JSON.stringify({ latitude: latitude, longitude: longitude, id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Ubicación compartida exitosamente!');
                } else {
                    alert('Error al compartir la ubicación.');
                }
            });
        }, function(error) {
            alert('Error obteniendo la ubicación: ' + error.message);
        });
    } else {
        alert('Geolocalización no soportada por este navegador.');
    }
};
</script>