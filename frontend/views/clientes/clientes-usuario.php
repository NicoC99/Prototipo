<?php

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = Yii::t('app', 'Datos de la empresa');


?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Datos
</p>


<h1><center>Datos de la empresa</center></h1>

<table class="table table-bordered">
    <tr>
        <th>Razón Social</th>
        <th>Cuit</th>
        <th>E-mail</th>
        <th>Teléfono</th>
        <th></th>
        
    </tr>
    <?php foreach($clientes as $row): ?>
    <tr>
        <td><?= $row->cliente_razon_social ?></td>
        <td><?= $row->cliente_cuit ?></td>
        <td><?= $row->cliente_mail ?></td>
        <td><?= $row->cliente_telefono ?></td>
        <td><?= Html::a('Modificar datos', ['clientes/modificar-cliente', 'cuit' => $row->cliente_cuit], ['class' => 'btn btn-primary']) ?></td>
    </tr>
    <?php endforeach ?>
</table>

