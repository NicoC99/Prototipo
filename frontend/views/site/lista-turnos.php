<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Lista de turnos');
?>

<p>
    <a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Lista de turnos
</p>

<h1>Lista de turnos</h1>

<table class="table table-bordered tabla-turnos">
    
        <tr>
            <th>Turno</th>
            <th>Fecha</th>
            <th>Horario</th>
            <th>Cliente</th>
            <th>Conductor</th>
            <th>Vehículo</th>
            <th>Producto</th>
            <th>Tn</th>
            <th>Estado</th>
            <th style="max-width: 120px;">Observaciones</th>
        </tr>

    
        <?php foreach ($turnos as $turno): ?>
            <tr>
                <td><?= $turno->turno_id ?></td>
                <td><?= $turno->turno_fecha ?></td>
                <td><?= $turno->turno_hora ?></td>
                <td> <?=$turno->clientes ? $turno->clientes->cliente_razon_social : 'N/A' ?></td>
                <td><?= $turno->conductor ? $turno->conductor->conductor_nombre : 'N/A' ?></td>
                <td><?= $turno->vehiculo ? $turno->vehiculo->vehiculo_patente : 'N/A' ?></td>
                <td><?= $turno->turno_producto ?></td>
                <td><?= $turno->turno_cantidad ?></td>
                <td><?= getEstado($turno->turno_estado) ?></td>
                <td style="max-width: 120px; word-wrap: break-word;"><?= Html::encode($turno->turno_observacion) ?></td>
            </tr>
        <?php endforeach; ?>
    
</table>

<?php
// Helper function to get the text representation of the state
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