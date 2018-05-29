<?php

/**
 * @var \yii\web\View $this
 * @var array $center
 * @var integer $zoom
 */

use app\modules\home\assets\GalleryAsset;
use app\modules\home\assets\HomeIndexAsset;

GalleryAsset::register($this);
HomeIndexAsset::register($this);
$this->title = Yii::t('app', 'Tourist objects');
?>

<?=\app\widgets\touristmap\TouristObjectsMap::widget([
        'zoom' => $zoom,
        'centerLatitude' => $center['lat'],
        'centerLongitude' => $center['lng'],
        'placesUrl' => \yii\helpers\Url::toRoute(array_merge(Yii::$app->request->queryParams, ['get-bound-places'])),
        'minZoom' => 6,
])?>

<div class="tourist-object-information">
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
</div>