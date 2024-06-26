<?php

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = Yii::t('app', 'Clientes');


?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("clientes/crear-cliente") ?>">Ingresar cliente</a> / Clientes
</p>
<br>
<a class="btn btn-lg btn-success" href="<?= Url::toRoute("clientes/crear-cliente")?>">Ingresar un nuevo cliente</a>
<h1><center>Clientes</center></h1>

<table class="table table-bordered">
    <tr>
        <th>Razón Social / Nombre</th>
        <th>Cuit / DNI</th>
        <th>Nombre de usuario</th>
        <th>Teléfono</th>
        <th>E-mail</th>
        
        <th>Acciones</th>
    </tr>
    <?php foreach($clientes as $row): ?>
    <tr>
        <td><?= $row->cliente_razon_social ?></td>
        <td><?= $row->cliente_cuit ?></td>
        <td><?= $row->usuario ? $row->usuario->usuario_nombre : 'N/A' ?></td>
        <td><?= $row->cliente_telefono ?></td>
        <td><?= $row->cliente_mail ?></td>
        
         <td>
            <?= Html::a('Editar', ['clientes/modificar-cliente', 'cuit' => $row->cliente_cuit], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Eliminar', ['clientes/eliminar-cliente', 'cuit' => $row->cliente_cuit], ['class' => 'btn btn-danger', 'data-confirm' => '¿Está seguro de que desea eliminar los datos del cliente?', 'data-method' => 'post']) ?>
       
         </td>
    </tr>
    <?php endforeach ?>
</table>