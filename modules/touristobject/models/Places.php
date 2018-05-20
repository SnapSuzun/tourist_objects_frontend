<?php

namespace app\modules\touristobject\models;


use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class Places
 * @package app\modules\touristobject\models
 *
 * @property string $_id
 * @property string $city_id
 * @property string $category
 * @property double $longitude
 * @property double $latitude
 * @property integer $uid
 * @property string $name
 *
 * @property Location $locationModel
 * @property Images[] $images
 * @property Images[] $tpoImages
 */
class Places extends ActiveRecord
{
    /**
     * Процент изображений с ТПО, ниже которого объект не считается туристическим
     * @var int
     */
    public $tpoPlaceBorderPercent = 15;

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'places';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'city_id', 'category', 'latitude', 'longitude', 'location', 'name'];
    }

    /**
     * @return \yii2tech\embedded\Mapping
     */
    public function embedLocationModel()
    {
        return $this->mapEmbedded('location', Location::class);
    }

    /**
     * @param array $condition
     * @return \yii\db\ActiveQueryInterface
     */
    public function getImages(array $condition = [])
    {
        $query = $this->hasMany(Images::class, ['place_id' => '_id']);
        if (!empty($condition)) {
            $query->andWhere($condition);
        }
        return $query;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getTpoImages()
    {
        return $this->getImages(['class' => Images::CLASS_TPO])->orderBy(['accuracy' => SORT_DESC]);
    }

    /**
     * @return bool
     */
    public function isTpoPlace()
    {
        $allImagesCount = $this->getImages()->count();
        if (!$allImagesCount) {
            return false;
        }
        $tpoImagesCount = $this->getTpoImages()->count();

        return ($tpoImagesCount / $allImagesCount) * 100 >= $this->tpoPlaceBorderPercent;
    }
}