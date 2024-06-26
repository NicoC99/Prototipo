<?php

use frontend\models\Chofer;
use yii\helpers\Html;
use\yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
$this->title = Yii::t('app', 'Lista de vehículos');

/** @var yii\web\View $this */
/** @var frontend\models\ChoferSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("vehiculo/crear-vehiculo") ?>">Ingresar vehículo</a> / Lista de vehículos
</p>
<a class="btn btn-lg btn-success" href="<?= Url::toRoute("vehiculo/crear-vehiculo")?>">Ingresar un nuevo vehículo</a>
<h1><center>Lista de vehículos</center></h1>

<table class="table table-bordered">
    <tr>
        <th>Patente</th>
        <th>Marca</th>
        <th>Vencimiento RTO</th>
        <th>Carga máxima en toneladas</th>
        <th>Cliente</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($model as $row): ?>
    <tr>
        <td><?= $row->vehiculo_patente ?></td>
        <td><?= $row->vehiculo_marca ?></td>
        <td><?= $row->vehiculo_vencimiento_rto ?></td>
        <td><?= $row->vehiculo_carga_maxima ?></td>
        <td><?= $row->clientes ? $row->clientes->cliente_razon_social : 'N/A' ?></td>
        
        <td>
            <?= Html::a('Editar', ['vehiculo/modificar-vehiculo', 'vehiculoPatente' => $row->vehiculo_patente], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('X', ['vehiculo/eliminar-vehiculo', 'vehiculoPatente' => $row->vehiculo_patente], ['class' => 'btn btn-danger', 'data-confirm' => '¿Estás seguro de que deseas borrar este registro?', 'data-method' => 'post']) ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>