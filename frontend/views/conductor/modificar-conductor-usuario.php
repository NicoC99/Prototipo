<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Editar Conductor: ' . $conductor->conductor_nombre . ' ';

?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($conductor, 'conductor_nombre')->textInput() ?>
<?= $form->field($conductor, 'conductor_dni')->textInput() ?>
<?= $form->field($conductor, 'conductor_telefono')->textInput() ?>
<?= $form->field($conductor, 'conductor_vigencia_licencia')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
    ])->label('Vigencia de licencia') ?>
<br>
<div class="form-group">
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>