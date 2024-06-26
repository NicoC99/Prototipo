<?php

use yii\helpers\Html;
use\yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('app', 'Lista de conductores');

/** @var yii\web\View $this */
/** @var frontend\models\ConductorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("conductor/crear-conductor") ?>">Ingresar conductor</a> / Lista de conductores
</p>
<a class="btn btn-lg btn-success" href="<?= Url::toRoute("conductor/crear-conductor")?>">Ingresar un nuevo conductor</a>
<h1><center>Lista de conductores</center></h1>

<table class="table table-bordered">
    <tr>
        <th>Nombre y apellido</th>
        <th>DNI</th>
        <th>Teléfono</th>
        <th>Vigencia de licencia</th>
        <th>Cliente</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($model as $row): ?>
    <tr>
        <td><?= $row->conductor_nombre ?></td>
        <td><?= $row->conductor_dni ?></td>
        <td><?= $row->conductor_telefono ?></td>
        <td><?= $row->conductor_vigencia_licencia ?></td>
        <td><?= $row->clientes->cliente_razon_social ?></td>
        <td>
            <?= Html::a('Editar', ['conductor/modificar-conductor', 'dni' => $row->conductor_dni], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('X', ['conductor/eliminar-conductor', 'dni' => $row->conductor_dni], ['class' => 'btn btn-danger', 'data-confirm' => '¿Estás seguro de que deseas borrar este registro?', 'data-method' => 'post']) ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>
 

