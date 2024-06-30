<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Horarios');
?>

<p>
    <a href="<?= Url::toRoute("site/turnos") ?>">Inicio</a> / Horarios
</p>

<div>
    <a class="btn btn-lg btn-success btn-agregar-horario" href="#">Agregar nuevo horario</a>
</div>

<h1><center>Horarios</center></h1>

<table class="table table-bordered tabla-turnos tabla-horarios">
    
    <tr>
        <th>Id</th>
        <th>Hora</th>
        <th>Acción</th>
    </tr>
    
    <tr id="agregar-horario" class="fila-edicion" style="display: none;">
        <?php $formulario = ActiveForm::begin([
            "action" => Url::to(['site/agregar-horario']),
            "method" => "post",
            'enableClientValidation' => true,
        ]); ?>
        <td></td>
        <td>
            <?= Html::dropDownList('hora', null,    ['00' => '00', '01' => '01', '02' => '02', '03' => '03',
                                                    '04' => '04', '05' => '05', '06' => '06', '07' => '07',
                                                    '08' => '08', '09' => '09', '10' => '10', '11' => '11',
                                                    '12' => '12', '13' => '13', '14' => '14', '15' => '15',
                                                    '16' => '16', '17' => '17', '18' => '18', '19' => '19',
                                                    '20' => '20', '21' => '21', '22' => '22', '23' => '23',
                                                    ], ['prompt' => 'Hora', 'class' => 'form-control']) ?>
            <?= Html::dropDownList('minutos', null, ['00' => '00', '15' => '15', '30' => '30', '45' => '45'], ['prompt' => 'Minutos', 'class' => 'form-control']) ?>
        </td>
        <td>
            <?= Html::hiddenInput('horario_hora', '', ['id' => 'horario_hora']) ?>
            <?= Html::submitButton("Agregar", ["class" => "btn btn-primary"]) ?>
        </td>
        <?php ActiveForm::end() ?>
    </tr>
    
    <?php foreach ($horarios as $horario): ?>
        <tr>
            <td><?= $horario->horario_id ?></td>
            <td><?= $horario->horario_hora ?></td>
            <td>
                <?= Html::a('Eliminar', ['site/eliminar-horario', 'horarioId' => $horario->horario_id], [
                    'class' => 'btn btn-sm btn-danger',
                    'data-confirm' => '¿Está seguro de que desea eliminar el horario?',
                    'data-method' => 'post'
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
document.querySelector('.btn-agregar-horario').addEventListener('click', function() {
    document.querySelector('#agregar-horario').style.display = 'table-row';
});

document.querySelector('form').addEventListener('submit', function(event) {
    var hora = document.querySelector('select[name="hora"]').value;
    var minutos = document.querySelector('select[name="minutos"]').value;
    if (hora !== '' && minutos !== '') {
        document.querySelector('#horario_hora').value = hora + ':' + minutos;
    } else {
        event.preventDefault();  // Prevent form submission if hour or minute is not selected
        alert('Por favor seleccione la hora y los minutos.');
    }
});
</script>