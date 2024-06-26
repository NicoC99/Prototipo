<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\modules\user\models\User $model */

$this->title = Yii::t('app', 'Update User: {name}', [
    'name' => $model->usuario_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario_id, 'url' => ['view', 'usuario_id' => $model->usuario_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
