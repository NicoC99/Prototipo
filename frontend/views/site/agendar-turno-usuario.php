<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
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
        <?= $form->field($model, 'conductor_dni')->dropDownList($conductor, ['prompt' => 'Seleccione']) ?>
    </div>
     </div>
    <br>
    <div class="col">
     <div class="form-group">
        <?= $form->field($model, 'vehiculo_patente')->dropDownList($vehiculo, ['prompt' => 'Seleccione']) ?>
    </div>
    </div>
     </div>
    <br>
    <div class="row">
        <div class="col">
    <div class="form-group">
        <?= $form->field($model, 'turno_hora')->dropDownList([
            '8:00' => '8:00',
            '9:00' => '9:00',
            '10:00' => '10:00',
            '11:00' => '11:00',
            '15:00' => '15:00',
            '16:00' => '16:00',
            '17:00' => '17:00',
            '18:00' => '18:00',
        ], ['prompt' => 'Seleccionar horario'])->label('Horario') ?>
    </div>
    </div>
    <br>
    <div class="col">
     <div class="form-group">
        <?= $form->field($model, 'turno_fecha', ['template' => "{label}\n{input}\n{hint}\n{error}"])->widget(\yii\jui\DatePicker::class, [
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control','autocomplete' => 'off'],
            'clientOptions' => [
                'beforeShowDay' => new \yii\web\JsExpression('function(date) {
                    var day = date.getDay();
                    // Días de la semana a deshabilitar: sábado (6) y domingo (0)
                    if (day === 6 || day === 0) {
                        return [false];
                    }

                    // Feriados a deshabilitar
                    var holidays = ["2023-01-01", "2023-02-27", "2023-02-28", "2023-03-24", "2023-04-02", "2023-04-14", 
    "2023-05-01", "2023-05-25", "2023-06-17", "2023-06-20", "2023-07-09", "2023-08-17", 
    "2023-10-12", "2023-11-20", "2023-12-08", "2023-12-25"]; // Reemplazar con tus fechas de feriados
                    var formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
                    if (holidays.indexOf(formattedDate) !== -1) {
                        return [false];
                    }
                    
                     var currentDate = new Date();
                    currentDate.setHours(0, 0, 0, 0); // Ajustar la hora actual a medianoche
            
                    // Deshabilitar fechas anteriores a la fecha actual
                    if (date < currentDate) {
                    return [false];
                    }
                    return [true];
                }'),
            ],
        ])->label('Fecha')
            ?>
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
                        <?= $form->field($model, 'turno_producto')->dropDownList([
                            'GBB' => 'GBB',
                            'EBB' => 'EBB',
                            'G50P' => 'G50P',
                            'E50P' => 'E50P',
                            'G25P' => 'G25P',
                            'E25P' => 'E25P',
                            'GPISO' => 'G25PISO',
                            'EPISO' => 'E25PISO',
                            'GPISO' => 'G50PISO',
                            'EPISO' => 'E50PISO',
                            'PNCG' => 'PNCG',
                            'PNCE' => 'PNCE',
                        ], ['prompt' => 'SELECCIONAR PRODUCTO']) ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <?= $form->field($model, 'turno_cantidad')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01])->label('Cantidad en toneladas') ?>
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
