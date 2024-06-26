<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', 'Ingresar vehículo');
?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("vehiculo/vehiculos") ?>">Lista de vehículos</a> / Ingresar vehículo
</p>
<h1><center>Ingresar vehículo</center></h1>
<?php $form = ActiveForm::begin([
    "method" => "post",
    'enableClientValidation' => true,
]);
?>
<div class="form-group">
    <?= $form->field($model, "vehiculo_patente")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($model, "vehiculo_marca")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($model, "vehiculo_vencimiento_rto")->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
    ])->label('Vencimiento RTO')?>
</div>
<div class="form-group">
    <?= $form->field($model, "vehiculo_carga_maxima")->input("text")?>
</div>
<br>
<?= Html::submitButton("Ingresar", ["class"=> "btn btn-primary"]) ?>
<?php $form->end() ?>

