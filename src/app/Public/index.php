<?php

declare (strict_types=1);

use App\App;

require __DIR__ . '/../../../vendor/autoload.php';

$app = App::initiate();

$app->run();
