<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;
use frontend\models\Turno;
$this->title = Yii::t('app', 'Turnos');
?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Turnos del día
</p>
<h1><center>Turnos agendados del día</h1>


 <?php if (!empty($turnosSeleccionados)): ?>
    
<table class="table table-bordered">
    <tr>
        <th>Turno</th>
        <th>Horario</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($turnosSeleccionados as $row): ?>
    <tr>
        <td><?= $row->turno_id ?></td>
        <td><?= $row->turno_hora ?></td>
        <td><?= $row->turno_fecha ?></td>
         <td>
            <?php
            // Mapea el estado del turno a su correspondiente texto
            $estados = [
                1 => 'Pedido',
                2 => 'Carga Lista',
                3 => 'Cargado',
                4 => 'Entregado',
                5 => 'Reprogramar'
            ];
            echo isset($estados[$row->turno_estado]) ? $estados[$row->turno_estado] : 'Desconocido';
            ?>
        </td>
         <td>
            <?= Html::a('Reprogramar', ['site/reprogramar-turno', 'turno_id' => $row->turno_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Eliminar', ['site/eliminar-turno', 'turno_id' => $row->turno_id], ['class' => 'btn btn-danger', 'data-confirm' => '¿Está seguro de que desea cancelar el turno?', 'data-method' => 'post']) ?>
        </td>
        
        
    </tr>
    <?php endforeach ?>
</table>
<?php else: ?>
    <p>No hay turnos disponibles para la fecha seleccionada.</p>
<?php endif; ?>

    <?php $model = new Turno();?>
    <center>
<?php $form = ActiveForm::begin(['action' => ['site/elegir-fecha'], 'method' => 'post', 'options' => ['class' => 'form-inline']]); ?>
    <div class="form-group">
        <?php echo $form->field($model, 'turno_fecha')->widget(DatePicker::class, [
            'name' => 'turno_fecha',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control','placeholder' => 'Seleccione otra fecha', 'style' => 'width: 200px', 'autocomplete' => 'off'],
        'clientOptions' => [
                'beforeShowDay' => new \yii\web\JsExpression('function(date) {
                    var day = date.getDay();
                    // Días de la semana a deshabilitar: sábado (6) y domingo (0)
                    if (day === 6 || day === 0) {
                        return [false];
                    }

                    // Feriados a deshabilitar
                    var holidays = ["2023-01-01", "2023-02-27", "2023-02-28", "2023-03-24", "2023-04-02", "2023-04-14", 
    "2023-05-01", "2023-05-25", "2023-06-17", "2023-06-20", "2023-07-09", "2023-08-17", 
    "2023-10-12", "2023-11-20", "2023-12-08", "2023-12-25"]; // Reemplazar con tus fechas de feriados
                    var formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
                    if (holidays.indexOf(formattedDate) !== -1) {
                        return [false];
                    }

                    return [true];
                }'),
            ],
        ])->label(false) 
        
        ?>
    </div>
    <div class="form-group">
        <br>
        <?php echo Html::submitButton('Ver Turnos', ['class' => 'btn btn-primary']); ?>
    </div>
<?php ActiveForm::end(); ?>
</center>

