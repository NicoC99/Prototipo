<?php

use frontend\models\Chofer;
use yii\helpers\Html;
use\yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\DatePicker;
$this->title = Yii::t('app', 'Lista de conductores');

/** @var yii\web\View $this */
/** @var frontend\models\ChoferSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Lista de vehículos
</p>
<div>
    <a class="btn btn-lg btn-success btn-ingresar-vehiculo" href="#">Ingresar un nuevo vehículo</a>
</div>
<h1><center>Lista de vehículos</center></h1>

<table class="table table-bordered tabla-sistema">
    <tr>
        <th>Patente</th>
        <th>Marca</th>
        <th>Vencimiento RTO</th>
        <th>Carga máxima en toneladas</th>
        <th>Acciones</th>
    </tr>
    <tr id="crear-vehiculo" class="fila-edicion" style="display: none;">
            <?php $formCrear = ActiveForm::begin([
                "action" => "crear-vehiculo",
                "method" => "post",
                'enableClientValidation' => true,
            ]); ?>
            <td><?= $formCrear->field($vehiculo, "vehiculo_patente")->textInput()->label(false) ?></td>
            <td><?= $formCrear->field($vehiculo, "vehiculo_marca")->textInput()->label(false) ?></td>
            <td>
                <?= $formCrear->field($vehiculo, "vehiculo_vencimiento_rto")->widget(DatePicker::className(), [
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
                ])->label(false) ?>
            </td>
            <td><?= $formCrear->field($vehiculo, "vehiculo_carga_maxima")->textInput()->label(false) ?></td>
            <td><?= Html::submitButton("Ingresar", ["class" => "btn btn-primary"]) ?></td>
            <?php ActiveForm::end() ?>
        </tr>
    <?php foreach($vehiculos as $vehiculo): ?>
    <tr>
        <td><?= $vehiculo->vehiculo_patente ?></td>
        <td><?= $vehiculo->vehiculo_marca ?></td>
        <td><?= $vehiculo->vehiculo_vencimiento_rto ?></td>
        <td><?= $vehiculo->vehiculo_carga_maxima ?></td>
        <td>
            <?= Html::a('Editar', 'javascript:void(0);', ['class' => 'btn btn-primary btn-modificar-vehiculo', 'data-patente' => $vehiculo->vehiculo_patente]) ?>
            <?= Html::a('X', ['vehiculo/eliminar-vehiculo', 'vehiculoPatente' => $vehiculo->vehiculo_patente], ['class' => 'btn btn-danger', 'data-confirm' => '¿Estás seguro de que deseas borrar este registro?', 'data-method' => 'post']) ?>
        </td>
    </tr>
    <tr id="modificar-vehiculo-<?= $vehiculo->vehiculo_patente ?>" class="fila-edicion" style="display: none;">
                <?php $formModificar = ActiveForm::begin([
                    "action" => ["modificar-vehiculo", 'vehiculoPatente' => $vehiculo->vehiculo_patente],
                    "method" => "post",
                    'enableClientValidation' => true,
                ]); ?>
                <td><?= $formModificar->field($vehiculo, 'vehiculo_patente')->textInput()->label(false) ?></td>
                <td><?= $formModificar->field($vehiculo, 'vehiculo_marca')->textInput()->label(false) ?></td>
                <td>
                    <?= $formModificar->field($vehiculo, 'vehiculo_vencimiento_rto')->widget(DatePicker::className(), [
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
                    ])->label(false) ?>
                </td>
                <td><?= $formModificar->field($vehiculo, 'vehiculo_carga_maxima')->textInput()->label(false) ?></td>
                <td><?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?></td>
                <?php ActiveForm::end(); ?>
            </tr>
    <?php endforeach ?>
</table>
 
<?php
// JavaScript para manejar la visibilidad del formulario al hacer clic en el botón "Ingresar un nuevo vehiculo" y "Editar vehiculo"
$script = <<< JS
    $(document).ready(function() {
        // Agregar evento de clic al botón "Ingresar un nuevo vehiculo"
        $('.btn-ingresar-vehiculo').click(function() {
            // Mostrar la fila del formulario
            $('#crear-vehiculo').toggle();
        });

        // Agregar evento de clic al botón "Editar vehiculo"
        $('.btn-modificar-vehiculo').click(function() {
            // Obtener el patente del vehiculo a modificar
            var patente = $(this).data('patente');
            // Mostrar el formulario de modificación correspondiente
            $('#modificar-vehiculo-' + patente).toggle();
        });
    });
JS;
$this->registerJs($script);
?>
