<?php

namespace app\components\mongodb;


use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;

/**
 * Class ActiveRecord
 * @package app\components\mongodb
 */
abstract class ActiveRecord extends \yii\mongodb\ActiveRecord implements ContainerInterface
{
    use ContainerTrait;

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->refreshFromEmbedded();
        return true;
    }
}