<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use RedBeanPHP\R as R;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dbHost = $_ENV['DB_HOST'] ?? '';
$dbName = $_ENV['DB_NAME'] ?? '';
$dbUser = $_ENV['DB_USER'] ?? '';
$dbPass = $_ENV['DB_PASSWORD'] ?? '';

if (!empty($dbHost) && !empty($dbName) && !empty($dbUser) && !empty($dbPass)) {
    try {
        R::setup("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    echo "Incomplete or missing environment secret(s).";
}

// template loader
$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader);

// start session
session_start();

$highlightJSData = getHighlightJSData();
// Check if a cookie called 'theme' exists
retrieveThemeCookie();


// check controller, params -> default
if (isset($_GET['controller'])) {
    $params = explode('/', $_GET['controller']);
    $controllerName = '\Controllers\\' . ucfirst($params[0]) . 'Controller';
    // check if controller exists
    if (!class_exists($controllerName)) {
        error(404, 'Controller \'' . ucfirst($params[0]) . 'Controller\' not found', '/');
    }
    // default controller
} else {
    $controllerName = "Controllers\HomeController";
}

// check method -> default
if (isset($params[1])) {
    $method = $params[1];
    // check if there is a post request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $method .= 'Post';
        // sent to the controller
        $controller = new $controllerName();
        $controller->$method();
        exit;
    }
    // check if method exists
    if (!method_exists($controllerName, $method)) {
        error(404, 'Method \'' . $params[1] . '\' not found', '/');
        exit;
    }
    // default method
} else {
    $method = 'index';
}

// call controller + corresponding method
$controller = new $controllerName();
echo $controller->$method();
