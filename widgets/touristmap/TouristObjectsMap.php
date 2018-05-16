<?php

namespace app\widgets\touristmap;


use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;

/**
 * Class TouristObjectsMap
 * @package app\modules\home\widgets
 */
class TouristObjectsMap extends Widget
{
    /**
     * @var double
     */
    public $centerLatitude = 0;
    /**
     * @var double
     */
    public $centerLongitude = 0;

    /**
     * @var integer
     */
    public $zoom = 14;

    /**
     * @var string
     */
    public $placesUrl = '';

    public $placeInfoUrl = '';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (is_null($this->centerLatitude) || is_null($this->centerLongitude)) {
            throw new InvalidConfigException(\Yii::t('common', 'Initial map center coordinates is not set.'));
        }
        if (empty($this->zoom)) {
            throw new InvalidConfigException(\Yii::t('common', 'Initial map zoom is not set.'));
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('tourist-objects-map', [
            'centerLongitude' => $this->centerLongitude,
            'centerLatitude' => $this->centerLatitude,
            'zoom' => $this->zoom,
            'placesUrl' => $this->placesUrl,
            'placeInfoUrl' => $this->placeInfoUrl
        ]);
    }
}