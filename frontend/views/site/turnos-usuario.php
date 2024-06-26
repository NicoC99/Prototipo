<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\jui\DatePicker;
use frontend\models\Turno;

$this->title = Yii::t('app', 'Turnos de hoy');


?>

<h1><center>Turnos del día</center></h1>

<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Horario</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Producto</th>
        <th>Tn</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($model as $row): ?>
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
        <td><?= $row->turno_producto?></td>
        <td><?= $row->turno_cantidad?></td>
        <td>
            <?= Html::a('Reprogramar', ['site/reprogramar-turno', 'id_turno' => $row->turno_id], ['class' => 'btn btn-sm btn-primary']) ?>
            <?= Html::a('X', ['site/eliminar-turno', 'id_turno' => $row->turno_id], ['class' => 'btn btn-sm btn-danger', 'data-confirm' => '¿Está seguro de que desea cancelar el turno?', 'data-method' => 'post']) ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>



    
   
<?php $model = new Turno();?>
<center>
<?php $form = ActiveForm::begin(['action' => ['site/elegir-fecha'], 'method' => 'post', 'options' => ['class' => 'form-inline']]); ?>
    <div class="form-group">
        <br>
        <br>
        <?php echo $form->field($model, 'turno_fecha')->widget(DatePicker::class, [
            'name' => 'fecha',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control','placeholder' => 'Seleccione una fecha', 'style' => 'width: 200px', 'autocomplete' => 'off'],
                
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
                    'placement' => 'top',
            ],
        ])->label('Ir a la fecha') 
        ?>
    </div>
    <div class="form-group">
        <br>
        <?php echo Html::submitButton('VER TURNOS', ['class' => 'btn btn-primary']); ?>
    </div>
    <br>
    <a class="btn btn-lg btn-primary" href="<?= Url::toRoute("site/lista-turnos")?>">LISTA COMPLETA DE TURNOS</a>
<?php ActiveForm::end(); ?>
</center>
<br>
<br>
<p style='text-align:center'>
    
<a class="btn btn-lg btn-success" href="<?= Url::toRoute("conductor/conductores")?>">CONDUCTORES</a>        
    
<a class="btn btn-lg btn-success" href="<?= Url::toRoute("vehiculo/vehiculos")?>">VEHICULOS</a>

<a class="btn btn-lg btn-success" href="<?= Url::toRoute("clientes/clientes")?>">DATOS EMPRESA</a>
<br>
<br>
<a class="btn btn-lg btn-success" href="<?= Url::toRoute("site/agendar-turno")?>">AGENDAR TURNOS</a>
     
</p>

 




