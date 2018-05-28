<?php

namespace app\modules\home\assets;

use yii\web\AssetBundle;

/**
 * Class GalleryAsset
 * @package app\modules\home\assets
 */
class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-gallery';
    public $css = [
        'css/blueimp-gallery.min.css',
    ];
    public $js = [
        'js/blueimp-gallery.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
