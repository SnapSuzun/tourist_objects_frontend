<?php
/**
 * @var \yii\web\View $this
 * @var double $centerLatitude
 * @var double $centerLongitude
 * @var integer $zoom
 * @var string $placesUrl
 */

use app\widgets\touristmap\assets\TouristObjectsMapAsset;

TouristObjectsMapAsset::register($this);
?>


<div class="tourist-objects-map-container">
    <div class="tourist-objects-map-canvas" data-default-latitude="<?= $centerLatitude ?>"
         data-default-longitude="<?= $centerLongitude ?>" data-default-zoom="<?= $zoom ?>"
         data-places-url="<?= $placesUrl ?>"></div>
</div>