<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Clientes');

?>

<p>
    <a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Clientes
</p>
<br>
<div>
    <a class="btn btn-lg btn-success btn-ingresar-cliente" href="#">Ingresar un nuevo cliente</a>
</div>
<h1><center>Clientes</center></h1>

<table class="table table-bordered tabla-sistema">
    <tr>
        <th>Razón Social / Nombre</th>
        <th>CUIT / DNI</th>
        <th>Nombre de usuario</th>
        <th>Teléfono</th>
        <th>E-mail</th>
        <th>Acciones</th>
    </tr>
    <tr id="crear-cliente" class="fila-edicion" style="display: none;">
        <?php $form = ActiveForm::begin(["action" => "crear-cliente", "method" => "post", 'enableClientValidation' => true]); ?>
        <td><?= $form->field($cliente, 'cliente_razon_social')->textInput()->label('Razón Social / Nombre') ?></td>
        <td><?= $form->field($cliente, 'cliente_cuit')->textInput()->label('CUIT / DNI')  ?></td>
        <td></td>
        <td><?= $form->field($cliente, 'cliente_telefono')->textInput()->label('Teléfono') ?></td>
        <td><?= $form->field($cliente, 'cliente_mail')->textInput()->label('E-mail') ?></td>
        <td colspan="2"><?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?></td>
        <?php ActiveForm::end(); ?>
    </tr>
    <?php foreach($clientes as $clienteItem): ?>
    <tr>
        <td><?= $clienteItem->cliente_razon_social ?></td>
        <td><?= $clienteItem->cliente_cuit ?></td>
        <td><?= $clienteItem->usuario ? $clienteItem->usuario->usuario_nombre : 'N/A' ?></td>
        <td><?= $clienteItem->cliente_telefono ?></td>
        <td><?= $clienteItem->cliente_mail ?></td>
        <td>
            <?= Html::a('Editar', 'javascript:void(0);', ['class' => 'btn btn-primary btn-modificar-cliente', 'data-cuit' => $clienteItem->cliente_cuit]) ?>
            <?= Html::a('X', ['clientes/eliminar-cliente', 'cuit' => $clienteItem->cliente_cuit], ['class' => 'btn btn-danger', 'data-confirm' => '¿Está seguro de que desea eliminar los datos del cliente?', 'data-method' => 'post']) ?>
        </td>
    </tr>
    <tr id="modificar-cliente-<?= $clienteItem->cliente_cuit ?>" class="fila-edicion" style="display: none;">
        <?php $form = ActiveForm::begin(["action" => ["modificar-cliente", 'cuit' => $clienteItem->cliente_cuit], "method" => "post", 'enableClientValidation' => true]); ?>
        <td><?= $form->field($clienteItem, 'cliente_razon_social')->textInput()->label('Razón Social / Nombre') ?></td>
        <td><?= $form->field($clienteItem, 'cliente_cuit')->textInput()->label('CUIT / DNI') ?></td>
        <td><?= $form->field($clienteItem, 'usuario_nombre')->textInput(['value' => $clienteItem->usuario ? $clienteItem->usuario->usuario_nombre : 'N/A', 'disabled' => true])->label('Nombre de usuario') ?></td>
        <td><?= $form->field($clienteItem, 'cliente_telefono')->textInput()->label('Teléfono') ?></td>
        <td><?= $form->field($clienteItem, 'cliente_mail')->textInput()->label('E-mail') ?></td>
        <td colspan="2"><?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?></td>
        <?php ActiveForm::end(); ?>
    </tr>
    <?php endforeach; ?>
</table>

<?php
$script = <<< JS
    $(document).ready(function() {
        $('.btn-ingresar-cliente').click(function() {
            $('#crear-cliente').toggle();
        });

        $('.btn-modificar-cliente').click(function() {
            var cuit = $(this).data('cuit');
            $('#modificar-cliente-' + cuit).toggle();
        });
    });
JS;
$this->registerJs($script);
?>