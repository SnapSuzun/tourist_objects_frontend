<?php
/**
 * Created by PhpStorm.
 * User: Snap
 * Date: 13.05.2018
 * Time: 18:42
 */

namespace app\components\filters;


use Yii;
use yii\base\ActionFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class AjaxFilter
 * @package app\components\filters
 */
class AjaxFilter extends ActionFilter
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return parent::beforeAction($action);
        }

        throw new BadRequestHttpException(Yii::t('common', 'Разрешен только Ajax запрос.'));
    }
}