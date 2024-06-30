<?php

use yii\helpers\Html;
use\yii\widgets\ActiveForm;
use yii\helpers\Url;

use yii\jui\DatePicker;
$this->title = Yii::t('app', 'Lista de conductores');

/** @var yii\web\View $this */
/** @var frontend\models\ConductorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Conductores
</p>
<div>
    <a class="btn btn-lg btn-success btn-ingresar-conductor" href="#">Ingresar un nuevo conductor</a>
</div>
<h1><center>Lista de conductores</center></h1>

<table class="table table-bordered tabla-sistema">
    <tr>
        <th>Nombre y apellido</th>
        <th>DNI</th>
        <th>Teléfono</th>
        <th>Vigencia de licencia</th>
        <th>Cliente</th>
        <th>Acciones</th>
    </tr>
    <tr id="crear-conductor" class="fila-edicion" style="display: none;">
    <?php $formulario = ActiveForm::begin(["action" => "crear-conductor","method" => "post",'enableClientValidation' => true,]);?>
    <td><?= $formulario->field($conductor, "conductor_nombre")->input("text")?></td>
    <td><?= $formulario->field($conductor, "conductor_dni")->input("text")?></td>
    <td><?= $formulario->field($conductor, "conductor_telefono")->input("text")?></td>
    <td><?= $formulario->field($conductor, "conductor_vigencia_licencia")->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
        ])->label('Vigencia de licencia') ?>
    </td>
    <td><?= $formulario->field($conductor, 'cliente_cuit')->dropDownList($clientes, ['prompt' => 'Seleccione']) ?></td>
    <td><?= Html::submitButton("Ingresar", ["class"=> "btn btn-primary"]) ?></td>
    <?php $formulario->end() ?>
    </tr>
    <?php foreach($conductores as $conductor): ?>
    <tr>
        <td><?= $conductor->conductor_nombre ?></td>
        <td><?= $conductor->conductor_dni ?></td>
        <td><?= $conductor->conductor_telefono ?></td>
        <td><?= $conductor->conductor_vigencia_licencia ?></td>
        <td><?= $conductor->clientes ? $conductor->clientes->cliente_razon_social : 'N/A' ?></td>
        <td>
            <?= Html::a('Editar', 'javascript:void(0);', ['class' => 'btn btn-primary btn-modificar-conductor', 'data-dni' => $conductor->conductor_dni]) ?>
            <?= Html::a('X', ['conductor/eliminar-conductor', 'dni' => $conductor->conductor_dni], ['class' => 'btn btn-danger', 'data-confirm' => '¿Estás seguro de que deseas borrar este registro?', 'data-method' => 'post']) ?>
        </td>
    </tr>
    <tr id="modificar-conductor-<?= $conductor->conductor_dni ?>" class="fila-edicion" style="display: none;">
        <?php $formulario = ActiveForm::begin([
        "action" => ["modificar-conductor", 'dni' => $conductor->conductor_dni],
    "method" => "post",
    'enableClientValidation' => true,
    ]);
    ?>
<td><?= $formulario->field($conductor, 'conductor_nombre')->textInput() ?></td>
<td><?= $formulario->field($conductor, 'conductor_dni')->textInput() ?></td>
<td><?= $formulario->field($conductor, 'conductor_telefono')->textInput() ?></td>
<td><?= $formulario->field($conductor, 'conductor_vigencia_licencia')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
    ])->label('Vigencia de licencia') ?></td>
<td><?= $formulario->field($conductor, 'cliente_cuit')->dropDownList($clientes, ['prompt' => 'Seleccione']) ?></td>
<td><?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?></td>

<?php ActiveForm::end(); ?>
    </tr>
    <?php endforeach ?>
</table>
 
<?php
// JavaScript para manejar la visibilidad del formulario al hacer clic en el botón "Ingresar conductor"
$script = <<< JS
    $(document).ready(function() {
        // Agregar evento de clic al botón "Ingresar un nuevo conductor"
        $('.btn-ingresar-conductor').click(function() {
            // Mostrar la fila del formulario
            $('#crear-conductor').toggle();
        });

        // Agregar evento de clic al botón "Editar conductor"
        $('.btn-modificar-conductor').click(function() {
            // Obtener el ID del conductor a modificar
            var dni = $(this).data('dni');
            // Mostrar el formulario de modificación correspondiente
            $('#modificar-conductor-' + dni).toggle();
        });
    });
JS;
$this->registerJs($script);
?>