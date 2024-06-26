<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', 'Reprogramar turno');

?>
<p>
<a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Reprogramar turno
</p>
<h1><center>Reprogramar turno</center></h1>
<div style="max-width: 200px; margin: 0 auto;">
    <?php $form = ActiveForm::begin();?>
     <br>

    <div class="form-group">
        <?= $form->field($model, 'turno_hora')->dropDownList([
            '7:00' => '7:00',
            '8:00' => '8:00',
            '9:00' => '9:00',
            '10:00' => '10:00',
            '11:00' => '11:00',
            '12:00' => '12:00',
        ], ['prompt' => 'Seleccionar horario'])->label('Horario') ?>
    </div>

    <br>
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
    
    <br>
    
    <div class="form-group">
        <?= Html::submitButton('Reprogramar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
