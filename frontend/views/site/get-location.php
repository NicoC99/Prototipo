<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $id string */

$this->title = 'Share Location';
?>

<div class="site-get-location">
    <h1><?= $this->title ?></h1>
    <p>Click the button below to share your location.</p>
    <button id="share-location" class="btn btn-primary">Share Location</button>
</div>

<script>
document.getElementById('share-location').onclick = function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const id = '<?= $id ?>';

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
                    alert('Location shared successfully!');
                } else {
                    alert('Failed to share location.');
                }
            });
        }, function(error) {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
};
</script>