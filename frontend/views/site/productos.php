<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Productos');
?>

<p>
    <a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Productos
</p>

<div>
    <a class="btn btn-lg btn-success btn-agregar-producto" href="#">Agregar nuevo producto</a>
</div>

<h1><center>Productos</center></h1>

<table class="table table-bordered tabla-turnos tabla-horarios">
    
    <tr>
        <th>Id</th>
        <th>Producto</th>
        <th>Acción</th>
    </tr>
    
    <tr id="agregar-producto" class="fila-edicion" style="display: none;">
        <?php $form = ActiveForm::begin([
            'id' => 'agregar-producto-form',
            'action' => Url::to(['site/agregar-producto']),
            'method' => 'post',
            'enableClientValidation' => true,
        ]); ?>
        <td></td>
        <td>
            <?= $form->field($model, 'producto_nombre')->textInput()->label(false) ?>
        </td>
        <td>
            <?= Html::submitButton("Agregar", ["class" => "btn btn-primary"]) ?>
        </td>
        <?php ActiveForm::end() ?>
    </tr>
    
    <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?= $producto->producto_id ?></td>
            <td><?= $producto->producto_nombre ?></td>
            <td>
                <?= Html::a('Eliminar', ['site/eliminar-producto', 'productoId' => $producto->producto_id], [
                    'class' => 'btn btn-sm btn-danger',
                    'data-confirm' => '¿Está seguro de que desea eliminar el producto?',
                    'data-method' => 'post'
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
document.querySelector('.btn-agregar-producto').addEventListener('click', function() {
    document.querySelector('#agregar-producto').style.display = 'table-row';
});

document.querySelector('form#agregar-producto-form').addEventListener('submit', function(event) {
    var producto_nombre = document.querySelector('#productos-producto_nombre').value;
    if (producto_nombre.trim() === '') {
        event.preventDefault();
        alert('Por favor ingrese el nombre del producto.');
    }
});
</script>