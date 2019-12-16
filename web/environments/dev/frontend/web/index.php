<?php
$config = require(__DIR__ . '/config.php');

$application = new yii\web\Application($config);
$application->run();
