<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */
/** @var \frontend\models\SignupForm $cliente */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Registrarse');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Por favor complete los siguientes campos para registrarse:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'cliente_razon_social')->label('Razón Social / Nombre') ?>
            
                <?= $form->field($model, 'usuario_nombre')->label('Nombre de usuario') ?>
  
                <?= $form->field($model, 'cliente_mail')->label('E-mail') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Contraseña') ?>
            
                <?= $form->field($model, 'cliente_cuit')->label('CUIT / DNI') ?>
            
                <?= $form->field($model, 'cliente_telefono')->label('Teléfono') ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
                
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>