<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;
use frontend\models\Turno;

$this->title = Yii::t('app', 'Turnos del día');
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

<p>
    <a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Turnos del día
</p>

<h1><center>Turnos agendados del día <?= date('d-m-Y', strtotime($fecha)) ?></center></h1>

<?php if (!empty($turnosSeleccionados)): ?>
<table class="table table-bordered tabla-turnos">
    <tr style="text-align: center">
        <th>#</th>
        <th>Horario</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Conductor</th>
        <th>Vehículo</th>
        <th>Producto</th>
        <th>Tn</th>
        <th>Estado</th>
        <th>Acciones</th>
        <th colspan="4">Observaciones</th>
    </tr>
    <?php foreach($turnosSeleccionados as $turno): ?>
    <tr style="text-align: center">
        <td><?= $turno->turno_id ?></td>
        <td><?= $turno->turno_hora ?></td>
        <td><?= date('d-m-Y', strtotime($turno->turno_fecha)) ?></td>
        <td><?= $turno->clientes ? $turno->clientes->cliente_razon_social : 'N/A' ?></td>
        <td><?= $turno->conductor ? $turno->conductor->conductor_nombre : 'N/A' ?></td>
        <td><?= $turno->vehiculo ? $turno->vehiculo->vehiculo_patente : 'N/A' ?></td>
        <td><?= $turno->turno_producto ?></td>
        <td><?= $turno->turno_cantidad ?></td>
        <td style="width: 25px">
            <?= Html::beginForm(['site/estado'], 'post', ['class' => 'form-inline']) ?>
            <?= Html::hiddenInput('turno_id', $turno->turno_id) ?>
            <?= Html::dropDownList('turno_estado', $turno->turno_estado, [
                1 => 'Pedido',
                2 => 'Carga Lista',
                3 => 'Cargado',
                4 => 'Entregado',
                5 => 'Reprogramar'
            ], ['class' => 'form-control turno-estado-dropdown', 'style' => 'width: 150px;', 'prompt' => 'Seleccione un estado']) ?>
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
    </tr>
    <tr class="fila-edicion" style="display: none;" id="reprogramarForm-<?= $turno->turno_id ?>">
        <td colspan="11">
            <?php $form = ActiveForm::begin(['action' => ['site/reprogramar-turno', 'turnoId' => $turno->turno_id], 'method' => 'post', 'options' => ['class' => 'form-inline']]); ?>
                <?= $form->field($turno, 'turno_fecha', ['template' => "{label}\n{input}\n{hint}\n{error}"])->widget(\yii\jui\DatePicker::class, [
    'dateFormat' => 'yyyy-MM-dd',
    'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
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
    <?php endforeach ?>
</table>
<?php else: ?>
    <p>No hay turnos disponibles para la fecha seleccionada.</p>
<?php endif; ?>

<?php $nuevaFecha = new Turno(); ?>
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
                    return [(day != 0 && day != 6)];
                }'),
                'placement' => 'bottom',
            ],
        ])->label('Elegir fecha') ?>
    </div>
    <div class="form-group">
        <br>
        <?= Html::submitButton('Ver Turnos', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
</center>