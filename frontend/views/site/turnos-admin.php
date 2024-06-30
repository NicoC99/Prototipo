<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use frontend\models\Turno;

$this->title = Yii::t('app', 'Turnos de hoy');
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
                
                // Inicializar DatePicker para el formulario de reprogramación
                $('#fecha-selector-' + turnoId).datepicker({
                    dateFormat: 'yy-mm-dd',
                    beforeShowDay: function(date) {
                        var day = date.getDay();
                        // Deshabilitar sábado (6) y domingo (0)
                        return [(day !== 0 && day !== 6)];
                    }
                });
            }
        });
    });
    
    // Inicializar DatePicker principal
    $('#fecha-selector-main').datepicker({
        dateFormat: 'yy-mm-dd', // Formato de fecha esperado por el controlador o modelo
        beforeShowDay: function(date) {
            var day = date.getDay();
            // Deshabilitar sábado (6) y domingo (0)
            return [(day !== 0 && day !== 6)];
        }
    });

    // Mostrar u ocultar observaciones al hacer clic en el input de observación
    document.querySelectorAll('.observacion-input').forEach(function(input) {
        input.addEventListener('click', function() {
            this.removeAttribute('readonly');
            const saveButton = this.closest('form').querySelector('.observacion-save-button');
            saveButton.style.display = 'inline-block';
        });
    });

    // Mostrar el botón de guardar al cambiar el estado del turno
    document.querySelectorAll('.turno-estado-dropdown').forEach(function(dropdown) {
        dropdown.addEventListener('change', function() {
            const saveButton = this.closest('form').querySelector('.save-button');
            saveButton.style.display = 'inline-block';
        });
    });

    // Manejar el botón de "Cancelar" en el formulario de reprogramación
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
    <thead>
        <tr style="text-align: center;">
            <th>#</th>
            <th>Horario</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Conductor</th>
            <th>Vehículo</th>
            <th>Producto</th>
            <th>Tn</th>
            <th style="width: 100px;">Estado</th>
            <th>Acciones</th>
            <th colspan="5" style="max-width: 300px;">Observaciones</th>
            <th>Ubicación</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($turnos as $turno): ?>
            <tr style="text-align: center;">
                <td><?= $turno->turno_id ?></td>
                <td><?= $turno->turno_hora ?></td>
                <td><?= date('d-m-Y', strtotime($turno->turno_fecha)) ?></td>
                <td> 
                    <?php 
                        $clienteRazonSocial = 'N/A';
                        if ($turno->clientes) {
                            $clienteRazonSocial = $turno->clientes->cliente_razon_social;
                        } elseif ($turno->conductor && $turno->conductor->clientes) {
                            $clienteRazonSocial = $turno->conductor->clientes->cliente_razon_social;
                        }
                        echo $clienteRazonSocial;
                    ?>
                </td>
                <td><?= $turno->conductor ? $turno->conductor->conductor_nombre : 'N/A' ?></td>
                <td><?= $turno->vehiculo ? $turno->vehiculo->vehiculo_patente : 'N/A' ?></td>
                <td><?= $turno->turno_producto ?></td>
                <td><?= $turno->turno_cantidad ?></td>
                <td>
                    <?= Html::beginForm(['site/estado'], 'post', ['class' => 'form-inline']) ?>
                    <?= Html::hiddenInput('turno_id', $turno->turno_id) ?>
                    <?= Html::dropDownList('turno_estado', $turno->turno_estado, [
                        1 => 'Pedido',
                        2 => 'Carga Lista',
                        3 => 'Cargado',
                        4 => 'Entregado',
                        5 => 'Reprogramar'
                    ], ['class' => 'form-control turno-estado-dropdown', 'prompt' => 'Seleccione un estado', 'style' => 'width: 100px']) ?>
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-sm btn-primary save-button', 'style' => 'display:none']) ?>
                    <?= Html::endForm() ?>
                </td>
                <td>
                    <?= Html::a('R', '#', [
                        'class' => 'btn btn-sm btn-primary reprogramar-button',
                        'data-turno-id' => $turno->turno_id
                    ]) ?>
                    <?= Html::a('X', ['site/eliminar-turno', 'turnoId' => $turno->turno_id], ['class' => 'btn btn-sm btn-danger', 'data-confirm' => '¿Está seguro de que desea cancelar el turno?', 'data-method' => 'post']) ?>
                </td>
                <td colspan="5" style="max-width: 300px; word-wrap: break-word;">
    <?= Html::beginForm(['site/editar-observacion'], 'post', ['class' => 'form-inline']) ?>
    <?= Html::hiddenInput('turno_id', $turno->turno_id) ?>
    <?= Html::textarea('turno_observacion', $turno->turno_observacion, [
        'class' => 'form-control observacion-input',
        'style' => 'width: 100%; min-height: 50px;', // Ajusta el tamaño del textarea según sea necesario
        'readonly' => true,
    ]) ?>
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-sm btn-primary observacion-save-button', 'style' => 'display:none']) ?>
    <?= Html::endForm() ?>
</td>
<td><a class="btn btn-warning btn-custom" href="<?= Url::toRoute('site/ubicacion') ?>">MAPA</a></td>
            </tr>
            <tr class="fila-edicion" style="display: none;" id="reprogramarForm-<?= $turno->turno_id ?>">
                <td colspan="11">
                    <?php $form = ActiveForm::begin(['action' => ['site/reprogramar-turno', 'turnoId' => $turno->turno_id], 'method' => 'post', 'options' => ['class' => 'form-inline']]); ?>
                        <?= $form->field($turno, 'turno_fecha', ['template' => "{label}\n{input}\n{hint}\n{error}"])->widget(\yii\jui\DatePicker::class, [
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'fecha-selector-' . $turno->turno_id],
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
                        <?= Html::submitButton('Reprogramar', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Cancelar', '#', ['class' => 'btn btn-danger cancelar-reprogramacion', 'data-turno-id' => $turno->turno_id]) ?>
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
        'id' => 'fecha-selector-main', // ID único para el elemento
        'class' => 'form-control',
        'placeholder' => 'Seleccione una fecha',
        'style' => 'width: 200px',
        'autocomplete' => 'off'
    ],
    'clientOptions' => [
        'beforeShowDay' => new \yii\web\JsExpression('function(date) {
            var day = date.getDay();
            // Deshabilitar sábado (6) y domingo (0)
            return [(day != 0 && day != 6)];
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

<p style='text-align:center'>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("conductor/conductores") ?>">CONDUCTORES</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("vehiculo/vehiculos") ?>">VEHÍCULOS</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("clientes/clientes") ?>">CLIENTES</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("site/agendar-turno") ?>">AGENDAR TURNO</a>
</p>
<p style='text-align:center'>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("site/horarios") ?>">MODIFICAR HORARIOS</a>
    <a class="btn btn-lg btn-success" href="<?= Url::toRoute("site/productos") ?>">MODIFICAR PRODUCTOS</a>
    
    
</p>