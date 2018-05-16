<?php

namespace app\modules\touristobject\models;


use yii\base\Model;

/**
 * Class Location
 * @package app\modules\touristobject\models
 *
 * @property string $type
 * @property array $coordinates
 */
class Location extends Model
{
    public $coordinates = [];
    public $type = '';

    /**
     * @return array
     */
    public function attributes()
    {
        return ['type', 'coordinates'];
    }

    /**
     * @return mixed|null
     */
    public function getLatitude()
    {
        return $this->coordinates[1] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getLongitude()
    {
        return $this->coordinates[0] ?? null;
    }
}