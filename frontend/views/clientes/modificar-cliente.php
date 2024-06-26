<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Editar datos de la empresa: ' . $cliente->cliente_razon_social;

?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / 
<a href="<?= Url::toRoute("clientes/clientes") ?>">Clientes</a> / Editar datos
</p>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($cliente, 'cliente_razon_social')->textInput() ?>
<?= $form->field($cliente, 'cliente_cuit')->textInput() ?>
<?= $form->field($cliente, 'cliente_mail')->textInput() ?>
<?= $form->field($cliente, 'cliente_telefono')->textInput() ?>
<br>
<div class="form-group">
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

