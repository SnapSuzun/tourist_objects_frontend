<?php

namespace app\modules\home\controllers;


use app\components\filters\AjaxFilter;
use app\modules\home\components\Map;
use app\modules\touristobject\models\Images;
use app\modules\touristobject\models\Places;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class IndexController
 * @package app\modules\map\controllers
 */
class IndexController extends Controller
{
    public function behaviors()
    {
        return [
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => [
                    'get-bound-places',
                    'get-place-info',
                ],
            ],
        ];
    }

    public function actionIndex(string $currentPosition = null)
    {
        $zoom = 15;
        $location = [
            'lat' => 0,
            'lng' => 0,
        ];

        if($currentPosition) {
            list($location, $zoom) = Map::parseCurrentPositionFromString($currentPosition);
        }

        return $this->render('index', [
            'zoom' => $zoom,
            'center' => $location
        ]);
    }

    public function actionGetBoundPlaces()
    {
        $post = \Yii::$app->request->post();

        $pointA = $post['points']['a'];
        $pointB = $post['points']['b'];

        $lngDiff = $pointB['lng'] - $pointA['lng'];
        $latDiff = $pointB['lat'] - $pointA['lat'];
        $outBoundRate = 1;
        $pointA['lng'] = min(max($pointA['lng'] - $lngDiff * $outBoundRate, -180), 180);
        $pointA['lat'] -= min(max($pointA['lat'] - $latDiff * $outBoundRate, -90), 90);
        $pointB['lng'] = max(min($pointB['lng'] + $lngDiff * $outBoundRate, 180), -180);
        $pointB['lat'] = max(min($pointB['lat'] + $latDiff * $outBoundRate, 90), -90);

        $query = Places::find()->where([
            'location' => [
                '$geoWithin' => [
                    '$geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [
                            [
                                [doubleval($pointA['lng']), doubleval($pointA['lat'])],
                                [doubleval($pointA['lng']), doubleval($pointB['lat'])],
                                [doubleval($pointB['lng']), doubleval($pointB['lat'])],
                                [doubleval($pointB['lng']), doubleval($pointA['lat'])],
                                [doubleval($pointA['lng']), doubleval($pointA['lat'])],
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $response = [
            'status' => 'success',
            'places' => []
        ];
        foreach ($query->batch(500) as $places) {
            /** @var Places $place */
            foreach ($places as $place) {
                if (\Yii::$app->request->get('show_all_places') || $place->isTpoPlace()) {
                    $response['places'][(string)$place->_id] = [
                        'coordinates' => [
                            'lat' => $place->locationModel->getLatitude(),
                            'lng' => $place->locationModel->getLongitude(),
                        ],
                        'name' => $place->name,
                        'category' => $place->category,
                        'id' => (string)$place->_id,
                        'information_url' => Url::toRoute(array_merge(\Yii::$app->request->queryParams, [
                            'place-info',
                            'id' => (string)$place->_id
                        ]))
                    ];
                }
            }
        }

        return $response;
    }

    /**
     * @param $id
     * @return string
     */
    public function actionPlaceInfo($id)
    {
        $place = $this->getPlace($id);

        $imageItems = [];

        /** @var Images[] $images */
        $query = $place->getTpoImages();
        if (\Yii::$app->request->get('show_all_places')) {
            $query = $place->getImages();
        }
        $images = $query->limit(100)->all();
        foreach ($images as $image) {
            $imageItems[] = [
                'url' => $image->thumbnail_url,
                'src' => $image->image_url,
                'options' => ['title' => $place->name],
                'imageOptions' => ['width' => 128, 'height' => 128]
            ];
        }

        return $this->renderPartial('touristic-object-info', ['place' => $place, 'imageItems' => $imageItems]);
    }

    /**
     * @param $id
     * @return Places
     * @throws \HttpInvalidParamException
     */
    protected function getPlace($id)
    {
        $place = Places::find()->where(['_id' => $id])->one();

        if (!$place) {
            throw new \HttpInvalidParamException(\Yii::t('common', 'Place not found.'));
        }

        return $place;
    }
}