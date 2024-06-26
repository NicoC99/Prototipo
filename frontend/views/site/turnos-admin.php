<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\DatePicker;
use frontend\models\Turno;
use Twilio\Rest\Client;


$this->title = Yii::t('app', 'Turnos de hoy');
?>

<h1><center>Turnos del día </center></h1>

<table class="table table-bordered">
    <tr style="text-align: center;">
        <th>#</th>
        <th>Horario</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Conductor</th>
        <th>Vehículo</th>
        <th>Producto</th>
        <th>Tn</th>
        <th colspan="2" style="text-align: center">Estado</th>
        <th>Acciones</th>
        <th colspan="3" style="max-width: 10px;">Observaciones</th>
    </tr>
    <?php foreach ($model as $row): ?>
        <tr>
            <td><?= $row->turno_id ?></td>
            <td><?= $row->turno_hora ?></td>
            <td><?= $row->turno_fecha ?></td>
            <td> 
                <?php 
                    $clienteRazonSocial = 'N/A';
                    if ($row->clientes) {
                        $clienteRazonSocial = $row->clientes->cliente_razon_social;
                    } elseif ($row->conductor && $row->conductor->clientes) {
                        $clienteRazonSocial = $row->conductor->clientes->cliente_razon_social;
                    }
                    echo $clienteRazonSocial;
                ?>
            </td>
            <td><?= $row->conductor ? $row->conductor->conductor_nombre : 'N/A' ?></td>
            <td><?= $row->vehiculo ? $row->vehiculo->vehiculo_patente : 'N/A' ?></td>
            <td><?= $row->turno_producto ?></td>
            <td><?= $row->turno_cantidad ?></td>
            <td colspan="2">
                <?= Html::beginForm(['site/estado'], 'post', ['class' => 'form-inline']) ?>
                <?= Html::hiddenInput('turno_id', $row->turno_id) ?>
                <?= Html::dropDownList('turno_estado', $row->turno_estado, [
                    1 => 'Pedido',
                    2 => 'Carga Lista',
                    3 => 'Cargado',
                    4 => 'Entregado',
                    5 => 'Reprogramar'
                ], ['class' => 'form-control']) ?>
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-sm btn-primary']) ?>
                <?= Html::endForm() ?>
            </td>
            <td>
                <?= Html::a('R', ['site/reprogramar-turno', 'turnoId' => $row->turno_id], ['class' => 'btn btn-sm btn-primary']) ?>
                <?= Html::a('X', ['site/eliminar-turno', 'turnoId' => $row->turno_id], ['class' => 'btn btn-sm btn-danger', 'data-confirm' => '¿Está seguro de que desea cancelar el turno?', 'data-method' => 'post']) ?>
                <?= Html::a('S', ['track/send-tracking-link', 'phoneNumber' => $row->conductor->conductor_telefono], ['class' => 'btn btn-sm btn-info']) ?>
            </td>
            <td colspan="2" style="max-width: 100px; word-wrap: break-word;">
                <span class="observaciones"><?= Html::encode($row->turno_observacion) ?></span>
            </td>
            <td>
                <?= Html::a('MOD', ['site/editar-observacion', 'turno_id' => $row->turno_id, 'fecha' => $row->turno_fecha], ['class' => 'btn btn-sm btn-primary']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php $model = new Turno(); ?>
<center>
<?php $form = ActiveForm::begin(['action' => ['site/elegir-fecha'], 'method' => 'post', 'options' => ['class' => 'form-inline']]); ?>
    <div class="form-group">
        <br><br>
        <?= $form->field($model, 'turno_fecha')->widget(DatePicker::class, [
            'name' => 'turno_fecha',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => [
                'class' => 'form-control',
                'placeholder' => 'Seleccione una fecha',
                'style' => 'width: 200px',
                'autocomplete' => 'off'
            ],
            'clientOptions' => [
                'beforeShowDay' => new \yii\web\JsExpression('function(date) {
                    var day = date.getDay();
                    if (day === 6 || day === 0) {
                        return [false];
                    }
                    var holidays = ["2023-01-01", "2023-02-27", "2023-02-28", "2023-03-24", "2023-04-02", "2023-04-14", 
                        "2023-05-01", "2023-05-25", "2023-06-17", "2023-06-20", "2023-07-09", "2023-08-17", 
                        "2023-10-12", "2023-11-20", "2023-12-08", "2023-12-25"];
                    var formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
                    if (holidays.indexOf(formattedDate) !== -1) {
                        return [false];
                    }
                    return [true];
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
