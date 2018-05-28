<?php

namespace app\modules\home\components;


use yii\base\Component;

/**
 * Class Map
 * @package app\modules\home\components
 */
class Map extends Component
{
    /**
     * @param string $currentPosition
     * @param string $splitter
     * @return array
     */
    public static function parseCurrentPositionFromString(string $currentPosition, $splitter = ','): array
    {
        $result = [];
        if ($currentPosition) {
            list($latitude, $longitude, $zoom) = explode($splitter, $currentPosition);
            $result = [
                [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ],
                $zoom,
            ];
        }

        return $result;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param float $zoom
     * @param string $splitter
     * @return string
     */
    public static function convertPositionToString(float $latitude, float $longitude, float $zoom, $splitter = '.'): string
    {
        return implode($splitter, [$latitude, $longitude, $zoom]);
    }
}