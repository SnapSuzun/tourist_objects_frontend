<?php

namespace app\modules\touristobject\models;


use app\components\mongodb\ActiveRecord;

/**
 * Class Images
 * @package app\modules\touristobject\models
 *
 * @property string $_id
 * @property string $place_id
 * @property double $accuracy
 * @property string $image_url
 * @property string $uid
 * @property string $class
 * @property string $thumbnail_url
 * @property string $shortcode
 * @property array $classes
 *
 * @property Places $place
 */
class Images extends ActiveRecord
{
    const CLASS_TPO = 'tpo';
    const CLASS_NOT_TPO = 'not_tpo';

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'images';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'place_id', 'accuracy', 'image_url', 'uid', 'class', 'thumbnail_url', 'shortcode', 'classes'];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPlace()
    {
        return $this->hasOne(Places::class, ['_id' => 'place_id']);
    }
}