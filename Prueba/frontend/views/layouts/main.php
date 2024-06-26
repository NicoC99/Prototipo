<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => "FGH ESTADISTICA",
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['/site/index'], 'visible' => !Yii::$app->user->isGuest,],
        [
        'label' => 'Plantas',
        'items' => [
            ['label' => 'Planta 1', 'url' => ['/site/planta1']],
            ['label' => 'Planta 2', 'url' => ['/site/planta2']],
            ['label' => 'Planta 3', 'url' => ['/site/planta3']],
            
        ],
            'visible' => !Yii::$app->user->isGuest,
    ],
        [
        'label' => 'Oficinas',
        'items' => [
            ['label' => 'Total oficinas 1', 'url' => ['/site/empleado']],
            ['label' => 'Total oficinas 2', 'url' => ['/site/empleado1']],
            
            ['label' => 'Oficinas mensual', 'url' => ['/site/empleadomes']],
            ['label' => 'Oficinas anual 1', 'url' => ['/site/empleadoanual']],
            ['label' => 'Oficinas anual 2', 'url' => ['/site/empleadoanual2']],
        ],
            'visible' => !Yii::$app->user->isGuest,
    ],
        ['label' => 'Cargar remito', 'url' => ['/site/importar'], 'visible' => !Yii::$app->user->isGuest,],
        
        
        
        ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Registrarse', 'url' => ['/site/signup']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Ingresar',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Salir (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted bg-dark">
    <div class="container">
        <b> <p class="float-start" style="color:white; ">FGH ESTADISTICA</p>
            <p class="float-end " style="color:white;">Desarrollado por <a href="http://infinito.ar" target="_blank" style="color:white;">INFINITO - CONSULTORIA INFORMATICA
                </a></p> </b>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
