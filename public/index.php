<?php

declare(strict_types=1);

use Aeon\Controller\InstagramController;
use Aeon\Controller\PixabayController;

require __DIR__ . '/../vendor/autoload.php';

$instagramController = new InstagramController();
$pixaBayController = new PixabayController();

$imageData = $pixaBayController->getImage('nature');
var_dump($imageData);

//$userId = $instagramController->getInstagramUserId();


