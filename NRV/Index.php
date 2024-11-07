<?php

require 'vendor/autoload.php';

session_start();

use NRV\Repository\FestivalRepository;
FestivalRepository::setConfig( 'config.ini' );

$d = new \NRV\dispatcher\Dispatcher();
$d->run();


//$party = new \NRV\event\Festival(1, "NRV", "2024 10 01 00:00", "2024 10 15 00:00");

//var_dump($party);
//var_dump($party->getDuration());

