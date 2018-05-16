<?php

namespace app\widgets\touristmap\assets;


use app\assets\GoogleMapAsset;
use yii\web\AssetBundle;

/**
 * Class HomeAsset
 * @package app\modules\home\assets
 */
class TouristObjectsMapAsset extends AssetBundle
{
    public $sourcePath = '@app/web/widgets/tourist-map';
    public $css = [
        'map.css'
    ];

    public $js = [
        'map.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        GoogleMapAsset::class,
    ];
}