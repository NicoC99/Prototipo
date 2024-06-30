<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Datos de la empresa');

?>
<p>
    <a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Datos
</p>

<h1><center>Datos de la empresa</center></h1>

<table class="table table-bordered tabla-sistema">
    <tr>
        <th>Razón Social / Nombre</th>
        <th>CUIT / DNI</th>
        <th>E-mail</th>
        <th>Teléfono</th>
        <th></th>
    </tr>
    <?php foreach($clientes as $cliente): ?>
    <tr>
        <td><?= $cliente->cliente_razon_social ?></td>
        <td><?= $cliente->cliente_cuit ?></td>
        <td><?= $cliente->cliente_mail ?></td>
        <td><?= $cliente->cliente_telefono ?></td>
        <td>
            <?= Html::a('Modificar datos', 'javascript:void(0);', [
                'class' => 'btn btn-primary btn-modificar-cliente',
                'data-cuit' => $cliente->cliente_cuit
            ]) ?>
        </td>
    </tr>
    <tr id="modificar-cliente-<?= $cliente->cliente_cuit ?>" class="fila-edicion" style="display: none;">
            <?php $form = ActiveForm::begin(["action" => ["modificar-cliente", 'cuit' => $cliente->cliente_cuit], "method" => "post", 'enableClientValidation' => true]); ?>
            <td><?= $form->field($cliente, 'cliente_razon_social')->textInput()->label('Razón Social / Nombre') ?></td>
            <td><?= $form->field($cliente, 'cliente_cuit')->textInput() ->label('CUIT / DNI')?></td>
            <td><?= $form->field($cliente, 'cliente_mail')->textInput()->label('E-mail') ?></td>
            <td><?= $form->field($cliente, 'cliente_telefono')->textInput()->label('Teléfono') ?></td>
            <td><?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?></td>
            <?php ActiveForm::end(); ?>
    </tr>
    <?php endforeach; ?>
</table>

<?php
$script = <<< JS
    $(document).ready(function() {
        $('.btn-modificar-cliente').click(function() {
            var cuit = $(this).data('cuit');
            $('#modificar-cliente-' + cuit).toggle();
        });
    });
JS;
$this->registerJs($script);
?>