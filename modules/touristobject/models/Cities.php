<?php

namespace app\modules\touristobject\models;


use app\components\mongodb\ActiveRecord;

/**
 * Class Cities
 * @package app\modules\touristobject\models
 *
 * @property string _id
 * @property integer $city_id
 * @property string $city_local_name
 * @property double $latitude
 * @property double $longitude
 * @property integer $uid
 * @property string $last_cursor
 * @property string $name
 */
class Cities extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'cities';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'city_id', 'city_local_name', 'latitude', 'longitude', 'uid', 'last_cursor', 'name'];
    }
}