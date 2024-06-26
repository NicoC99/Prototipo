<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Turno;

$this->title = 'Editar Observación';
$this->params['breadcrumbs'][] = ['label' => 'Turnos', 'url' => ['site/turnos']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="turno-editar-observacion">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Aquí puedes editar la observación del turno:</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($turno, 'turno_observacion')->textInput() ?>
    
    <br>
<div class="form-group">
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
</div>