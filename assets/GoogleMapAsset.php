<?php

namespace app\assets;


use Yii;
use yii\web\AssetBundle;

/**
 * Class GoogleMapAsset
 *
 * Registers the google maps api
 *
 * To update the key or other options like language, version, or library
 * use the Asset Bundle customization.
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#customizing-asset-bundles
 * To get key, please visit https://code.google.com/apis/console/
 *
 *      'components' => [
 *          'assetManager' => [
 *              'bundles' => [
 *                  'app\assets\GoogleMapAsset' => [
 *                      'options' => [
 *                          'key' => 'this_is_my_key',
 *                          'language' => 'id',
 *                          'version' => '3.1.18'
 *                      ]
 *                  ]
 *              ]
 *          ],
 *      ],
 *
 * @package app\assets
 */
class GoogleMapAsset extends AssetBundle
{
    /**
     * Sets options for the google map
     * @var array
     */
    public $options = [];
    /**
     * @inheritdoc
     */
    public function init()
    {

        // BACKWARD COMPATIBILITY
        // To configure please, add `googleMapsApiKey` parameter to your application configuration
        // file with the value of your API key. To get yours, please visit https://code.google.com/apis/console/.
        $key = @Yii::$app->params['googleMapsApiKey'];
        // To configure please, add `googleMapsLibraries` parameter to your application configuration
        $libraries = @Yii::$app->params['googleMapsLibraries'];
        // To configure please, add `googleMapsLanguage` parameter to your application configuration
        $language = @Yii::$app->params['googleMapsLanguage'];

        $this->options = array_merge($this->options, array_filter([
            'key' => $key,
            'libraries' => $libraries,
            'language' => $language
        ]));
        // BACKWARD COMPATIBILITY

        $this->js[] = '//maps.googleapis.com/maps/api/js?'. http_build_query($this->options);
    }
}