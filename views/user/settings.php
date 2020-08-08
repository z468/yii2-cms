<?php

use smart\widgets\ActiveForm;
use yii\helpers\Html;

// Title
$title = Yii::t('cms', 'User settings');
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'firstName') ?>
    
    <?= $form->field($model, 'lastName') ?>

    <div class="form-group form-buttons row">
        <div class="col-sm-10 offset-sm-2">
            <?= Html::submitButton(Yii::t('cms', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('cms', 'Change password'), ['user/password'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
