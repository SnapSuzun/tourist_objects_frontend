<?php
$params = [
    'adminEmail' => 'admin@touristobjects.com',
    'applicationName' => 'Tourist objects'
];

$localParams = require __DIR__ . '/params.local.php';

return \yii\helpers\ArrayHelper::merge($params, $localParams);
