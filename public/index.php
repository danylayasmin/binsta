<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use RedBeanPHP\R as R;

require_once '../vendor/autoload.php';
//connect to database
R::setup('mysql:host=localhost;dbname=db', 'user', 'password');

// template loader
$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader);

$data = [
    'project_name' => 'Binsta',
];

displayTemplate('welcome.twig', $data);