<?php

namespace lh\controller;

use lh\classes\Application;

/**
 * Class UsersController
 * @package lh\controller
 */
class UsersController extends PrimaryController
{

    /**
     * UsersController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->intrusionPlaceName = 'user_id';
        $this->outputTemplate ='users/list.php';
    }

    /**
     *
     */
    protected function initModels()
    {
        $this->model = $this->users;
        $this->modelName = 'users';
        $this->datasetName = 'users';
        $this->itemName = 'user';
    }

    /**
     * @return bool
     */
    public function getDataset()
    {
        return $this->model->getList();
    }

    /**
     * @return null
     */
    public function getCurrentItem()
    {
        return $this->model->getItem($this->currentItemID);
    }

    /**
     * @return array
     */
    public function getEmptyItem()
    {
        return ['login' => '', 'password' => '', 'email' => ''];
    }

    /**
     * @return bool
     */
    public function setInputData()
    {
        $result = (
            $this->getParamLogin('login') &&
            $this->isUserUnique() &&
            $this->getParamPassword('password') &&
            $this->getParamMail('email') &&
            $this->getParamType('type')
        );
        return $result;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getParamLogin($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match('/.{5,}/is', $_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this->errors[$name] = 'Error in user login';
        }
        return $success;
    }

    /**
     * @return bool
     */
    public function isUserUnique()
    {
        $result = false;
        if (isset($this->data['login'])) {
            $result = $this->model->isUserUnique($this->data['login'], $this->currentItemID);
        } else {
            $this->errors['unique'] = 'User with entered login exist';
        }
        return $result;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getParamMail($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match('/.+@.+\..+/is', $_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this->errors[$name] = 'Error in user email';
        }
        return $success;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getParamPassword($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match('/.{5,}/is', $_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this->errors[$name] = 'Error in user password';
        }
        return $success;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getParamType($name)
    {
        if (isset($_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } else {
            $data[$name] = QUEST_CODE;
            $success = true;
            $this->messages[$name] = 'Using default user type';
        }
        return $success;
    }

    /**
     * @return bool
     */
    public function checkPasswords()
    {
        $result = false;
        if (isset($_POST['password1']) && isset($_POST['password2']) && ($_GET['password1'] === $_GET['password2'])) {
            $this->data['password'] = $_POST['password1'];
            $result = true;
        } else {
            $this->errors['password'] = 'Error in user password';
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function checkUser()
    {
        $success = false;
        if (isset($_SESSION['user']['type']) &&
            $_SESSION['user']['type'] == ADMIN_CODE) {
            $success = true;
        }
        return $success;
    }

    /**
     *
     */
    public function login()
    {
        $block = '';
        if ($this->getParamLogin('login') && $this->getParamPassword('password')) {
            $user = $this->model->getUserByLogin($this->data['login']);
            if ($user['password'] === $this->data['password']) {
                $_SESSION['user'] = $user;
                header('Location: index.php');
            } else {
                $this->errors['login'] = 'Password doesn\'t match user password';
                $block = $this->render(
                    $this->modelName . '/login.php',
                    [$this->itemName => $this->data]
                );
            }
        } else {
            $this->errors['input'] = 'Bad input';
            $block = $this->render(
                $this->modelName . '/login.php',
                [$this->itemName => $this->data]
            );
        }
        $this->outputTemplate ='users/blank.php';
        $this->renderResultPage(
            [$this->intrusionPlaceName => '',
            'block' => $block,
            'type' => '']
        );
    }

    /**
     *
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
    }

    /**
     *
     */
    public function register()
    {
        $block = '';
        if ($this->getParamLogin('login') &&
            $this->isUserUnique() &&
            $this->getParamMail('email') &&
            $this->checkPasswords()
        ) {
            $this->data['type'] = USER_CODE;
            
            $isRegistered = $this->model->add($this->data);
            if ($isRegistered) {
                $this->userRegistered();
                $this->messages['register'] = 'Registration successful';
                $block = $this->render(
                    $this->modelName . '/welcome.php',
                    [$this->itemName => $this->data]
                );
            } else {
                $errorInfo = $this->model->getLastPDOError()['2'];
                $this->errors['register'] = 'User is not registered. Database Error: ' . $errorInfo['2'];
                $block = $this->render(
                    $this->modelName . '/register.php',
                    [$this->itemName => $this->data]
                );
            }
        } else {
            $block = $this->render(
                $this->modelName . '/register.php',
                [$this->itemName => $this->data]
            );
        }
        $this->outputTemplate ='users/blank.php';
        $this->renderResultPage(
            [$this->intrusionPlaceName => '',
            'block' => $block,
            'type' => '']
        );
    }


    /**
     *
     */
    public function userRegistered()
    {
    }


    /**
     *
     */
    public function welcome()
    {
        if (isset($_SESSION['user']['type'])) {
            switch ($_SESSION['user']['type']) {
                case ADMIN_CODE:
                    $block = $this->render('users/admin.php');
                    break;
                case USER_CODE:
                    $block = $this->render('topics/list.php');
                    //$this->router->TopicsController->list();
                    //$block =  $this->render('users/admin.php');
                    break;
                default: //'Гость'
                    $block = $this->render('topics/list.php');
                    //$block =  $this->render('users/admin.php');
                    break;
            }
        } else {
            $block = $this->render('users/welcome.php');
        }
        $this->outputTemplate ='users/blank.php';
        $this->renderResultPage(
            [$this->intrusionPlaceName => '',
            'block' => $block,
            'type' => '']
        );
    }

    /**
     *
     */
    public function gotoLogin()
    {
        $block = $this->render('users/login.php');
        $this->outputTemplate ='users/blank.php';
        $this->outputTemplate ='users/blank.php';
        $this->renderResultPage(
            [$this->intrusionPlaceName => '',
            'block' => $block,
            'type' => '']
        );
    }

    /**
     *
     */
    public function gotoRegister()
    {
        $block =  $this->render('users/register.php');
        $this->outputTemplate ='users/blank.php';
        $this->renderResultPage(
            [$this->intrusionPlaceName => '',
            'block' => $block,
            'type' => '']
        );
    }
}
