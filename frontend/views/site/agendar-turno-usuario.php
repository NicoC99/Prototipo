<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', 'Agendar turno');
?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Agendar turno
</p>
<h1><center>Agendar turno</center></h1>
<div style="max-width: 800px; margin: 0 auto;">
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'enableClientValidation' => true,
    ]); ?>
     <br>
     <div class="row">
         
     <br>
     <div class="col">
     <div class="form-group">
        <?= $form->field($turno, 'conductor_dni')->dropDownList($conductor, ['prompt' => 'Seleccione']) ?>
    </div>
     </div>
    <br>
    <div class="col">
     <div class="form-group">
        <?= $form->field($turno, 'vehiculo_patente')->dropDownList($vehiculo, ['prompt' => 'Seleccione']) ?>
    </div>
    </div>
     </div>
    <br>
    <div class="row">
        <div class="col">
    <div class="form-group">
        <?= $form->field($turno, 'turno_hora')->dropDownList($horarios, ['prompt' => 'Seleccionar horario'])->label('Horario') ?>
    </div>
    </div>
    <br>
    <div class="col">
     <div class="form-group">
        <?= $form->field($turno, 'turno_fecha', ['template' => "{label}\n{input}\n{hint}\n{error}"])->widget(\yii\jui\DatePicker::class, [
    'dateFormat' => 'yyyy-MM-dd',
    'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
    'clientOptions' => [
        'beforeShowDay' => new \yii\web\JsExpression('function(date) {
            var day = date.getDay();
            if (day === 6 || day === 0) {
                return [false];
            }
            
            var currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);
            if (date < currentDate) {
                return [false];
            }
            return [true];
        }'),
    ],
])->label('Fecha') ?>
    </div>
    </div>
    </div>
    <br>
    <div class="form-group">
        <label>Seleccione el producto y cantidad:</label><br><br>
        <div id="camposProducto" style="width: 800px;">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <?= $form->field($turno, 'turno_producto')->dropDownList($productos, ['prompt' => 'SELECCIONAR PRODUCTO'])->label('Producto') ?>
                       
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <?= $form->field($turno, 'turno_cantidad')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01])->label('Cantidad en toneladas') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    
    <div  style="width: 150px; margin: 0 auto; ">
    <?= Html::submitButton('AGENDAR', ['class' => 'btn btn-primary']) ?>
</div>

    <?php ActiveForm::end() ?>
</div>
