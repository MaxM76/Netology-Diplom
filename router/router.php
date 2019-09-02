<?php

namespace localhost\router;

use localhost\classes\Application;

//use localhost\controller\UsersController;

include 'router/consts.php';

/**
 * Class Router
 * @package localhost\router
 */
class Router
{
    /**
     * Имя объекта контроллера
     * @var string
     */
    public $controllerName = '';
    /**
     * Полное имя объекта контроллера
     * @var string
     */
    public $controllerFullName = '';

    /**
     * @var string
     */
    public $action = '';
    /**
     * @var string
     */
    public $method = '';

    /**
     * Router constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Метод считывает информацию о контроллере и запрашиваемом действии
     */
    private function read()
    {
        if (!isset($_GET['c']) || !isset($_GET['a'])) {
            $this->controllerName = 'users' . 'Controller';
            $this->action = 'default';
        } else {
            $this->controllerName = $_GET['c'] . 'Controller';
            $this->action = $_GET['a'];
        }
    }

    /**
     * Метод устанавливает имя метода контроллера в соответствии с запрашиваемым действием
     */
    private function setMethod()
    {
        switch ($this->controllerName) {
            case "usersController":
                $this->method = USERS_METHODS[$this->action];
                //$_SESSION['user']['type'] == USER_TYPES[ADMIN_STR])
                break;
            case "topicsController":
                $this->method = TOPICS_METHODS[$this->action][$this->application->userType];
                break;
            case "questionsController":
                $this->method = QUESTIONS_METHODS[$this->action][$this->application->userType];
                break;
            case "answersController":
                $this->method = ANSWERS_METHODS[$this->action][$this->application->userType];
                break;
        }
    }

    /**
     * Метод устанавливает полное имя контроллера в соответствии с запросом
     */
    private function setController()
    {
        $this->controllerFullName = '\localhost\controller\\'.$this->controllerName;
    }

    /**
     * Главный метод роутера.
     *
     * Установка имени объекта контроллера и вызаваемого метода контроллера
     */
    public function init()
    {
        $this->read();
        $this->setMethod();
        $this->setController();
    }
}
