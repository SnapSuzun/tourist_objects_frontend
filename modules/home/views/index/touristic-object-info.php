<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\touristobject\models\Places $place
 * @var array $imageItems
 */
?>

<div
<div class="row">
    <div class="col-lg-12 text-center">
        <h1><?= $place->name ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 text-center">
        <?= dosamigos\gallery\Gallery::widget([
            'items' => $imageItems,
            'options' => ['id' => 'gallery_2'],
        ]); ?>
    </div>
</div>
