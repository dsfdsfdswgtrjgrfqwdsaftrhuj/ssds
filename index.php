<?php

session_start();

header("Access-Control-Allow-Origin: https://filmo-sfera-production-7bc4.up.railway.app");
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

require __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;
use App\Controller\AuthController;
use App\Middleware\AuthMiddelware;
use App\Middleware\AdminMiddelware;
use App\Controller\CookieController;
use App\Controller\ProfileController;
use App\Controller\FilmsController;
use App\Controller\CommentController;

$router = new Router();
$authController = new AuthController();
$authMiddelware = new AuthMiddelware();
$adminMiddelware = new AdminMiddelware();
$cookieController = new CookieController();
$profileController = new ProfileController();
$filmsController = new FilmsController();
$commentController = new CommentController();

$router->post('/checkcookie', fn() => $cookieController->checkcookie());
$router->post('/login', fn() => $authController->login());
$router->post('/register', fn() => $authController->register());
$router->get('/displayName', [$authMiddelware, fn() => $profileController->displayName()]);
$router->post('/changeusername', [$authMiddelware, fn() => $profileController->changeusername()]);
$router->get('/displayAvatar', [$authMiddelware, fn() => $profileController->displayAvatar()]);
$router->put('/uploadawatar', [$authMiddelware, fn() => $profileController->uploadawatar()]);
$router->put('/uploadFilm', [$adminMiddelware, fn() => $filmsController->uploadFilm()]);
$router->get('/loadfilm', [$authMiddelware, fn() => $filmsController->loadfilm()]);
$router->post('/searchfilm', [$authMiddelware, fn() => $filmsController->searchfilm()]);
$router->post('/addcomm', [$authMiddelware, fn() => $commentController->addcomm()]);
$router->get('/getcomment', [$authMiddelware, fn() => $commentController->getcomment()]);
$router->post('/logout', [$authMiddelware, fn() => $authController->logout()]);


$router->run();
