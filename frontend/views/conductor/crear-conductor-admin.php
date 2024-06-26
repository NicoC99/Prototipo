<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', 'Ingresar conductor');
?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> /
<a href="<?= Url::toRoute("conductor/conductores") ?>">Lista de conductores</a> / Ingresar conductor
</p>
<h1><center>Ingresar conductor</center></h1>
<?php $form = ActiveForm::begin([
    "method" => "post",
    'enableClientValidation' => true,
]);
?>
<div class="form-group">
    <?= $form->field($conductor, "conductor_nombre")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($conductor, "conductor_dni")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($conductor, "conductor_telefono")->input("text")?>
</div>
<div class="form-group">
    <?= $form->field($conductor, "conductor_vigencia_licencia")->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
    ])->label('Vigencia de licencia') ?>
</div>

<div class="form-group">
        <?= $form->field($conductor, 'cliente_cuit')->dropDownList($clientes, ['prompt' => 'Seleccione']) ?>
</div>
<br>

<?= Html::submitButton("Ingresar", ["class"=> "btn btn-primary"]) ?>
<?php $form->end() ?>