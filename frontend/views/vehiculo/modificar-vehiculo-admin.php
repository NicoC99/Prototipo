<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Editar Vehículo: ' . $vehiculos->vehiculo_patente . '. Cliente: ' . $vehiculos->clientes->cliente_razon_social;

?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("vehiculo/vehiculos") ?>">Lista de vehículos</a> / Editar vehículo
</p>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($vehiculos, 'vehiculo_patente')->textInput() ?>
<?= $form->field($vehiculos, 'vehiculo_marca')->textInput() ?>
<?= $form->field($vehiculos, 'vehiculo_vencimiento_rto')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
    ])->label('Vencimiento RTO')?>
<?= $form->field($vehiculos, "vehiculo_carga_maxima")->textInput()?>
<?= $form->field($vehiculos, 'cliente_cuit')->dropDownList($clientes, ['prompt' => 'Seleccione']) ?>
<br>
<div class="form-group">
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
