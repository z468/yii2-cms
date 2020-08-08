<?php

use yii\helpers\Html;
use smart\cms\widgets\Alert;
use smart\cms\assets\AppAsset;

$asset = AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::tag('link', '', ['rel' => 'shortcut icon', 'href' => $asset->baseUrl . '/img/logo32.png', 'type' => 'image/png']) ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="login-wrap bg-light">
    <div class="login-block">
        <div class="login-header">
            <span class="header-title">yii<strong>smart</strong></span>
            <span class="header-version"><?= \smart\cms\Module::VERSION ?></span>
        </div>
        <?= $content ?>
        <?= Alert::widget() ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
