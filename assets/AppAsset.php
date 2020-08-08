<?php

namespace smart\cms\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/dist';

    public $css = [
        'css/main.css',
    ];

    public $js = [
        'js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'smart\cms\assets\FontAwesomeAsset',
    ];
}
