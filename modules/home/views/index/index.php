<?php

/**
 * @var \yii\web\View $this
 * @var \dosamigos\google\maps\Map $map
 * @var array $center
 * @var integer $zoom
 */

\dosamigos\gallery\GalleryAsset::register($this);
\app\modules\home\assets\HomeIndexAsset::register($this);
?>

<?=\app\widgets\touristmap\TouristObjectsMap::widget([
        'zoom' => $zoom,
        'centerLatitude' => $center['lat'],
        'centerLongitude' => $center['lng'],
        'placesUrl' => \yii\helpers\Url::toRoute(array_merge(Yii::$app->request->queryParams, ['get-bound-places'])),
        'minZoom' => 6,
])?>

<div class="tourist-object-information"></div>