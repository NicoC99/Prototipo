<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hola <?= $user->usuario_nombre ?>,

Siga el siguiente enlace para verificar su correo electrónico:

<?= $verifyLink ?>
