<?php

namespace JonathanRayln\Core;

use JonathanRayln\Core\Base\Controller;
use JonathanRayln\Core\Contracts\UserInterface;
use JonathanRayln\Core\Database\Database;
use JonathanRayln\Core\Http\Request;
use JonathanRayln\Core\Http\Response;
use JonathanRayln\Core\Http\Router;

class Application
{
    public static string $ROOT_DIR;
    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?UserInterface $user;
    public View $view;

    public static Application $app;
    public ?Controller $controller = null;

    public function __construct($rootPath, array $config)
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;

        $this->userClass = $config['userClass'];

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $userClass = new $this->userClass();
            $primaryKey = $userClass->primaryKey();
            $this->user = $userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode()); // TODO: this line causes an error for codes that are not INT, like PDO errors.

            echo $this->view->renderView('_error', [
                'exception' => $e,
            ]);
        }
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(UserInterface $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};

        $this->session->set('user', $primaryValue);

        return true;
    }

    public function logout()
    {
        $this->user = null;

        $this->session->remove('user');
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }
}