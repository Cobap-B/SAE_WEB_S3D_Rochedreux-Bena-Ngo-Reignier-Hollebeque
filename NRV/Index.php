<?php

require 'vendor/autoload.php';

session_start();

//use deefy\repository\DeefyRepository;
//DeefyRepository::setConfig( 'config.ini' );

$d = new \NRV\dispatcher\Dispatcher();
$d->run();


