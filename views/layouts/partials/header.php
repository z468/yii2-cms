<?php

use yii\bootstrap4\Nav;
use yii\helpers\Html;

?>
<nav class="navbar navbar-expand fixed-top navbar-dark bg-dark">
    <div class="container-fluid">

        <button type="button" id="sidebar-collapse" class="btn btn-dark">
            <i class="fas fa-bars"></i>
        </button>

        <?= Html::a('yii<strong>smart</strong>', Yii::$app->getHomeUrl(), ['class' => 'header-logo']) ?>

        <?= Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => Yii::$app->params['menu-header'],
        ]) ?>

    </div>

    <?= $this->render('sidebar') ?>
</nav>
