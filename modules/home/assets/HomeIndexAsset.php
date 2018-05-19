<?php

namespace app\modules\home\assets;


use yii\web\AssetBundle;

/**
 * Class HomeIndexAsset
 * @package app\modules\home\assets
 */
class HomeIndexAsset extends AssetBundle
{
    public $sourcePath = '@app/web/modules/home/index';
    public $css = [
        'index.css'
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}