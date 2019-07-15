<?php

include 'router/consts.php';

class Router
{
    private $controller = null; // controller object
    private $controllerFile = ''; // controller object file
    public $controllerName = ''; // controller object name
    public $action = '';
    public $method = '';

    private $db = null;
    public $actionsButtons = [];
    public $userType = USER_TYPES['Гость'];


    function __construct()
    {
        $this ->  connectDb();
    }

    function configDb()
    {
        $config = include 'config.php';
        return $config;
    }

    function connectDb()
    {
        $config = $this -> configDb();
        try {
            $this -> db = new PDO(
                'mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8',
                $config['user'],
                $config['pass']
            );
        } catch (PDOException $e) {
            die('Database error: '.$e -> getMessage().'<br/>');
        }
    }


    function getDatabase()
    {
        return $this -> db;
    }

    function checkUser()
    {
        if (isset($_SESSION['user']['type'])) {
            $this -> userType = $_SESSION['user']['type'];
        } 
    }

    function read()
    {
        /*
        print_r($_SERVER['REQUEST_URI']);

        echo '<br>';
         $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'].'#current-topic';
        print_r($_SERVER['REQUEST_URI']);

        echo '<br>';
        */        
        if (! isset($_GET['c']) || ! isset($_GET['a'])) {
            $this -> controllerName = 'users' . 'Controller';
            $this -> action = 'default';
            //echo 'default controller<br/>';
        } else {
            $this -> controllerName = $_GET['c'] . 'Controller';
            $this -> action = $_GET['a'];
            //echo 'not default controller<br/>';
        }

        switch ($this -> controllerName) {
            case "usersController":
                $this -> method = USERS_METHODS[$this -> action];
                //$_SESSION['user']['type'] == USER_TYPES['Администратор'])
                break;
            case "topicsController":
                $this -> method = TOPICS_METHODS[$this -> action][$this -> userType];
                break;
            case "questionsController":
                $this -> method = QUESTIONS_METHODS[$this -> action][$this -> userType];
                break;
            case "answersController":
                $this -> method = ANSWERS_METHODS[$this -> action][$this -> userType];
                break;
        }
    }


    function initActionButtons()
    {
        switch ($this -> userType) {
            case USER_TYPES['Администратор']:
                $this -> actionButtons = 
                    ['gotoStartPage', 'manageUsers', 'manageContent', 'logout'];
                break;
            case USER_TYPES['Пользователь']:
                $this -> actionButtons = 
                    ['gotoStartPage', 'logout', 'explore'];
                break;
            case USER_TYPES['Гость']:
                $this -> actionButtons = 
                    ['gotoStartPage', 'login', 'register', 'explore'];
                break;
        }
    }

    function addActionButton($actionButton)
    {
        if (!(in_array($actionButton, $this -> actionButtons))) {
            $this -> actionButtons[] = $actionButton;
        }    
    }

    function removeActionButton($actionButton)
    {
        $position = array_search($actionButton, $this -> actionButtons);
        if (!$position) {
            unset($this -> actionButtons[$position]);       
        }
    }

    function createController()
    {
        $controllerName = $this -> controllerName;
        if (class_exists($controllerName)) {
            $this -> controller = new $controllerName($this); // создает объект-контроллер            
        }
    }

    function run()
    {
        if (method_exists($this -> controller, $this -> method)) {
            $method = $this -> method;
            $this -> controller -> $method(); // вызывает метод объекта-контроллера
        }
    }
    
}

include 'controller/UsersController.php';
include 'controller/TopicsController.php';
include 'controller/QuestionsController.php';
include 'controller/AnswersController.php';

$r = new Router;
$r -> checkUser();
$r -> read();
$r -> createController();
$r -> initActionButtons();
$r -> run();
