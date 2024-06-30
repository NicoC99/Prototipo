<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\DatePicker;
use frontend\models\Turno;

$this->title = Yii::t('app', 'Turnos de hoy');
?>

<?php
// Registro de assets de jQuery y jQuery UI
$this->registerAssetBundle(\yii\web\JqueryAsset::class);
$this->registerAssetBundle(\yii\jui\JuiAsset::class);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar u ocultar formulario de reprogramación al hacer clic en el botón "R"
    document.querySelectorAll('.reprogramar-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const turnoId = this.getAttribute('data-turno-id');
            const reprogramarForm = document.getElementById('reprogramarForm-' + turnoId);
            if (reprogramarForm) {
                reprogramarForm.style.display = 'table-row';
                setupDatePicker(turnoId); // Llamar a la función para inicializar DatePicker
            }
        });
    });

    // Función para inicializar el DatePicker para un formulario específico
    function setupDatePicker(turnoId) {
        $(`#fecha-selector-${turnoId}`).datepicker({
            dateFormat: 'yy-mm-dd',
            beforeShowDay: function(date) {
                var day = date.getDay();
                return [(day !== 0 && day !== 6)];
            },
            // Otras opciones según sea necesario
        });
    }

    // Cerrar formulario de reprogramación al hacer clic en el botón "Cancelar"
    document.querySelectorAll('.cancelar-reprogramacion').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const turnoId = this.getAttribute('data-turno-id');
            const reprogramarForm = document.getElementById('reprogramarForm-' + turnoId);
            if (reprogramarForm) {
                reprogramarForm.style.display = 'none';
            }
        });
    });
});
</script>

<h1><center>Turnos del día - <?= date('d-m-Y') ?></center></h1>

<table class="table table-bordered tabla-turnos">
    <thead style="text-align: center;">
        <tr>
            <th>#</th>
            <th>Horario</th>
            <th>Fecha</th>
            <th>Conductor</th>
            <th>Vehículo</th>
            <th>Producto</th>
            <th>Tn</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($turnos as $turno): ?>
        <tr style="text-align: center;">
            <td><?= $turno->turno_id ?></td>
            <td><?= $turno->turno_hora ?></td>
            <td><?= date('d-m-Y', strtotime($turno->turno_fecha)) ?></td>
           
            <td><?= $turno->conductor ? $turno->conductor->conductor_nombre : 'N/A' ?></td>
            <td><?= $turno->vehiculo ? $turno->vehiculo->vehiculo_patente : 'N/A' ?></td>
            <td><?= $turno->turno_producto ?></td>
            <td><?= $turno->turno_cantidad ?></td>
            <td><?= getEstado($turno->turno_estado) ?></td>
            <td>
                <?= Html::a('R', '#', [
                        'class' => 'btn btn-sm btn-primary reprogramar-button',
                        'data-turno-id' => $turno->turno_id
                    ]) ?>
                <?= Html::a('X', ['site/eliminar-turno', 'turnoId' => $turno->turno_id], ['class' => 'btn btn-sm btn-danger', 'data-confirm' => '¿Está seguro de que desea cancelar el turno?', 'data-method' => 'post']) ?>
                </td>
            
        </tr>
        <tr class="fila-edicion" style="display: none;" id="reprogramarForm-<?= $turno->turno_id ?>">
            <td colspan="11">
                <?php $form = ActiveForm::begin(['action' => ['site/reprogramar-turno', 'turnoId' => $turno->turno_id], 'method' => 'post', 'options' => ['class' => 'form-inline']]); ?>
                <?= $form->field($turno, 'turno_fecha', ['template' => "{label}\n{input}\n{hint}\n{error}"])->widget(\yii\jui\DatePicker::class, [
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'id' => 'fecha-selector-' . $turno->turno_id, // ID único para el elemento
                        'class' => 'form-control datepicker-input', // Asegurar que tenga la clase datepicker-input
                        'autocomplete' => 'off'
                    ],
                    'clientOptions' => [
                        'beforeShowDay' => new \yii\web\JsExpression('function(date) {
                            var day = date.getDay();
                            if (day === 6 || day === 0) {
                                return [false];
                            }

                            var currentDate = new Date();
                            currentDate.setHours(0, 0, 0, 0);
                            if (date < currentDate) {
                                return [false];
                            }
                            return [true];
                        }'),
                    ],
                ])->label('Fecha') ?>
                 <?= $form->field($turno, 'turno_hora')->dropDownList($horarios, ['prompt' => 'Seleccionar horario'])->label('Horario') ?>
                <div class="form-group">
                    <?= Html::submitButton('Reprogramar', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Cancelar', '#', ['class' => 'btn btn-danger cancelar-reprogramacion', 'data-turno-id' => $turno->turno_id]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<center>
    <?php $form = ActiveForm::begin(['action' => ['site/elegir-fecha'], 'method' => 'post', 'options' => ['class' => 'form-group']]); ?>
    <div class="form-group">
        <br><br>
        <?= $form->field(new Turno(), 'turno_fecha')->widget(DatePicker::class, [
            'name' => 'turno_fecha',
            'dateFormat' => 'yyyy-MM-dd', // Formato de fecha deseado
            'options' => [
                'id' => 'fecha-selector', // ID único para el elemento
                'class' => 'form-control',
                'placeholder' => 'Seleccione una fecha',
                'style' => 'width: 200px',
                'autocomplete' => 'off'
            ],
            'clientOptions' => [
                'beforeShowDay' => new \yii\web\JsExpression('function(date) {
                    var day = date.getDay();
                    // Deshabilitar sábado (6) y domingo (0)
                    return [(day !== 0 && day !== 6)];
                }'),
                'placement' => 'bottom',
            ],
        ])->label('Elegir fecha') ?>
    </div>
    <div class="form-group">
        <br>
        <?= Html::submitButton('VER TURNOS', ['class' => 'btn btn-primary']) ?>
    </div>
    <br>
    <a class="btn btn-lg btn-primary" href="<?= Url::toRoute("site/lista-turnos") ?>">LISTA COMPLETA DE TURNOS</a>
    <?php ActiveForm::end(); ?>
</center>
<br>

<p style='text-align:center'>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("conductor/conductores") ?>">CONDUCTORES</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("vehiculo/vehiculos") ?>">VEHÍCULOS</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("clientes/clientes") ?>">CLIENTES</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("site/agendar-turno") ?>">AGENDAR TURNO</a>
</p>

<?php
// Función para transformar el número de estado en el equivalente en texto.
function getEstado($estado)
{
    $estados = [
        1 => 'Pedido',
         2 => 'Carga lista',
        3 => 'Cargado',
        4 => 'Entregado',
        5 => 'Reprogramar'
    ];

    return isset($estados[$estado]) ? $estados[$estado] : 'Desconocido';
}
?>