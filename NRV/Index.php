<?php

require 'vendor/autoload.php';

session_start();

use NRV\Repository\FestivalRepository;
FestivalRepository::setConfig( 'config.ini' );

$d = new \NRV\dispatcher\Dispatcher();
$d->run();


