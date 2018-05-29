<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\touristobject\models\Places $place
 * @var array $imageItems
 */
use yii\bootstrap\Html;

?>

<div class="tourist-objects-images-container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1><?= $place->name ?></h1>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center">
            <div id="gallery_2">
                <?php foreach ($imageItems as $item): ?>
                    <?= Html::a(Html::img($item['url'] ?? $item['src'], $item['imageOptions'] ?? []), $item['src'] ?? $item['url'], $item['options'] ?? []) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>