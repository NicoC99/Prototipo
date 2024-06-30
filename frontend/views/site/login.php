<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Ingresar');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Por favor complete los siguientes campos para iniciar sesión:')?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'usuario_nombre')->textInput(['autofocus' => true])->label("Nombre de usuario") ?>

                <?= $form->field($model, 'password')->passwordInput()->label("Contraseña") ?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label("Recuérdame") ?>

                <div class="my-1 mx-0" style="color:#999;">
                    <?= Yii::t('app', 'Si olvidaste tu contraseña puedes ')?><?= Html::a( Yii::t('app', 'reestablecerla'), ['site/request-password-reset']) ?>.
                   <br>
                    ¿Necesita un nuevo correo electrónico de verificación? <?= Html::a('Reenviar', ['site/resend-verification-email']) ?>
                
                </div>

                <div class="form-group">
                    <?= Html::submitButton( Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
