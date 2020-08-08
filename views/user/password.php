<?php

use smart\widgets\ActiveForm;
use yii\helpers\Html;

// Title
$title = Yii::t('cms', 'Change password');
$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'oldPassword')->passwordInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    
    <?= $form->field($model, 'confirm')->passwordInput() ?>

    <div class="form-group form-buttons row">
        <div class="col-sm-10 offset-sm-2">
            <?= Html::submitButton(Yii::t('cms', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
