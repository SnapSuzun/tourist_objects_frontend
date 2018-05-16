<?php

namespace app\modules\home\controllers;


use app\components\filters\AjaxFilter;
use app\modules\touristobject\models\Images;
use app\modules\touristobject\models\Places;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\layers\BicyclingLayer;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\overlays\PolylineOptions;
use dosamigos\google\maps\services\DirectionsRenderer;
use dosamigos\google\maps\services\DirectionsRequest;
use dosamigos\google\maps\services\DirectionsService;
use dosamigos\google\maps\services\DirectionsWayPoint;
use dosamigos\google\maps\services\TravelMode;
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

    public function actionIndex()
    {
        $place = Places::find()->one();

        return $this->render('index', ['zoom' => 14,
                                       'center' => [
                                           'lat' => $place ? $place->locationModel->getLatitude() : 0,
                                           'lng' => $place ? $place->locationModel->getLongitude() : 0
                                       ]
        ]);
    }

    public function actionGetBoundPlaces()
    {
        $post = \Yii::$app->request->post();

        $pointA = $post['points']['a'];
        $pointB = $post['points']['b'];

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
        foreach ($query->batch() as $places) {
            /** @var Places $place */
            foreach ($places as $place) {
                if ($place->isTpoPlace()) {
                    $response['places'][(string)$place->_id] = [
                        'coordinates' => [
                            'lat' => $place->locationModel->getLatitude(),
                            'lng' => $place->locationModel->getLongitude(),
                        ],
                        'name' => $place->name,
                        'category' => $place->category,
                        'id' => (string)$place->_id,
                        'information_url' => Url::toRoute(['place-info', 'id' => (string)$place->_id])
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
        $images = $place->getTpoImages()->limit(100)->all();
        foreach ($images as $image) {
            $imageItems[] = [
                'url' => $image->image_url,
                'src' => $image->thumbnail_url,
                'options' => ['title' => $place->name],
                'imageOptions' => ['width' => 128, 'height' => 128]
            ];
        }

        return $this->renderAjax('touristic-object-info', ['place' => $place, 'imageItems' => $imageItems]);
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