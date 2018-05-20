<?php
/**
 * @var \yii\web\View $this
 * @var double $centerLatitude
 * @var double $centerLongitude
 * @var integer $zoom
 * @var string $placesUrl
 * @var integer $minZoom
 * @var integer $maxZoom
 */

use app\widgets\touristmap\assets\TouristObjectsMapAsset;

$asset = TouristObjectsMapAsset::register($this);
?>

<div class="tourist-objects-map-container">
    <input class="tourist-objects-map-search google-map-controls" type="text" placeholder="<?=Yii::t('app', 'Search')?>">
    <div class="tourist-objects-map-canvas" data-default-latitude="<?= $centerLatitude ?>"
         data-default-longitude="<?= $centerLongitude ?>" data-default-zoom="<?= $zoom ?>"
         data-places-url="<?= $placesUrl ?>" data-min-zoom="<?=$minZoom?>" data-max-zoom="<?=$maxZoom?>"></div>
</div>