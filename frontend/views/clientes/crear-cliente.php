<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', 'Ingresar cliente');
?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("clientes/clientes") ?>">Clientes</a> / Ingresar cliente
</p>
<h1><center>Ingresar cliente</center></h1>
<?php $form = ActiveForm::begin([
    "method" => "post",
    'enableClientValidation' => true,
]);
?>
<div class="form-group">
    <?= $form->field($cliente, "cliente_razon_social")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($cliente, "cliente_cuit")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($cliente, "cliente_mail")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($cliente, "cliente_telefono")->input("text")?>
</div>
<br>
<?= Html::submitButton("Ingresar", ["class"=> "btn btn-primary"]) ?>
<?php $form->end() ?>
