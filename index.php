<?php

declare (strict_types = 1);

ini_set("session.gc_maxlifetime", '31536000');
ini_set('session.cookie_lifetime', '31536000');
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '1');

session_start();

require_once 'framework/Utils/debug.php';
require_once 'recaptchalib.php';
require_once 'vendor/autoload.php';

$config = require_once 'config/config.php';

$location = $config->get('project.location');
$route = require_once 'routes/routes.php'; // variable $location is require

use App\Helper\Assets;
use Phantom\Controller\AbstractController;
use Phantom\Exception\AppException;
use Phantom\Exception\ConfigurationException;
use Phantom\Helper\Request;
use Phantom\View;

$request = new Request($_GET, $_POST, $_SERVER, $_FILES);

try {
    AbstractController::initConfiguration($config, $route);
    View::initConfiguration($location);
    Assets::initConfiguration($location);

    $type = $request->getParam('type', 'general');
    $phantom = "\Phantom\Controller\\" . ucfirst($type) . "Controller";
    $app = "\App\Controller\\" . ucfirst($type) . "Controller";

    if (class_exists($app)) {
        $controller = new $app($request);
    } else if (class_exists($phantom)) {
        $controller = new $phantom($request);
    } else {
        dump("TODO [index.php]: Controller [" . $type . "] doen't exists");
        // TODO Controller doen't exists
        die();
    }

    $controller->run();

} catch (ConfigurationException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo 'Problem z aplikacją, proszę spróbować za chwilę.';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (\Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji </h1>';
    dump($e);
}
