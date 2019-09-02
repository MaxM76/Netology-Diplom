<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 18.08.2019
 * Time: 15:21
 */

namespace localhost\classes;

use localhost\controller\PrimaryController;
use localhost\router\Router;
use localhost\controller\UsersController;

/**
 * Class Application
 * @package localhost\classes
 */
class Application
{
    /**
     * @var Router
     */
    public $router;
    /**
     * @var \PDO
     */
    private $db = null;
    /**
     * @var int
     */
    public $userType;
    /**
     * @var PrimaryController
     */
    private $controller = null;
    /**
     * @var array
     */
    public $actionButtons = [];

    /**
     * Application constructor.
     *
     * Создает объект роутера, присоединяет базу данных, определяет тип пользователя
     */
    public function __construct()
    {
        $this->router = new Router($this);
        $this->connectDb();
        $this->checkUser();
    }

    /**
     * Метод возвращает настройки для базы данных, хранящиеся в файле config.php
     * @return mixed
     */
    private function configDb()
    {
        $config = include 'config.php';
        return $config;
    }

    /**
     * Метод подсоединят базу данных. В случае неудачи бросает исключение PDOException
     * @return void
     * @throws \PDOException
     */
    protected function connectDb()
    {
        $config = $this->configDb();
        try {
            $this->db = new \PDO(
                'mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8',
                $config['user'],
                $config['pass']
            );
        } catch (\PDOException $e) {
            die('Database error: '.$e->getMessage().'<br/>');
        }
    }

    /**
     * Геттер для свойства db
     * @return \PDO
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * Метод определяет тип пользователя
     */
    private function checkUser()
    {
        if (isset($_SESSION['user']['type'])) {
            $this->userType = $_SESSION['user']['type'];
        } else {
            $this->userType = QUEST_CODE;
        }
    }

    /**
     * Метод инициализирует навигационные кнопки в зависимости от типа пользователя
     */
    public function initActionButtons()
    {
        switch ($this->userType) {
            case ADMIN_CODE:
                $this->actionButtons = ['gotoStartPage', 'manageUsers', 'manageContent', 'logout'];
                break;
            case USER_CODE:
                $this->actionButtons = ['gotoStartPage', 'logout', 'explore'];
                break;
            case QUEST_CODE:
                $this->actionButtons = ['gotoStartPage', 'login', 'register', 'explore'];
                break;
        }
        if (isset($_SESSION['user'])) {
            $this->addActionButton('logout');
        }
    }

    /**
     * @param array $actionButton
     */
    public function addActionButton($actionButton)
    {
        if (!(in_array($actionButton, $this->actionButtons))) {
            $this->actionButtons[] = $actionButton;
        }
    }

    /**
     * @param array $actionButton
     */
    public function removeActionButton($actionButton)
    {
        $position = array_search($actionButton, $this->actionButtons);
        if (!$position) {
            unset($this->actionButtons[$position]);
        }
    }

    /**
     *
     */
    private function createController()
    {
        if (class_exists($this->router->controllerFullName)) {
            $controllerName = $this->router->controllerFullName;
            $this->controller = new $controllerName($this);
        } else {
            $this->controller = new UsersController($this);
            $this->router->method = USERS_METHODS['error'];
        }
    }

    /**
     * Главный метод объекта Application. Запускает приложение
     *
     * Инициирует роутер, создает контроллер, инициирует кнопки навигации, запускает метод объекта-контроллера
     *
     * @return void
     */
    public function run()
    {
        $this->router->init();
        $this->createController();
        $this->initActionButtons();
        if (method_exists($this->controller, $this->router->method)) {
            $method = $this->router->method;
            $this->controller->$method(); // вызывает метод объекта-контроллера
        } else {
            //$this->controller->$method(); // error page
        }
    }
}
