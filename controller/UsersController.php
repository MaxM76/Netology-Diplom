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
    protected function getParamLogin($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match(LOGIN_REGEXP, $_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this->errors[__METHOD__] = USER_LOGIN_ERR_MSG . LOGIN_REQUIREMENTS;
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
            $this->errors[__METHOD__] = USER_EXIST_ERR_MSG;
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
        if (isset($_POST[$name]) && preg_match(EMAIL_REGEXP, $_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this->errors[__METHOD__] = USER_EMAIL_ERR_MSG;
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
        if (isset($_POST[$name]) && preg_match(PASSWORD_REGEXP, $_POST[$name])) {
            $this->data[$name] = $_POST[$name];
            $success = true;
        } elseif ($_POST['type'] == QUEST_CODE) {
            $this->data[$name] = '';
            $success = true;
        } else {
            $this->errors[__METHOD__] = USER_PASSWORD_ERR_MSG . PASSWORD_REQUIREMENTS;
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
            $this->messages[__METHOD__] = USER_DEFAULT_TYPE_MSG;
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
            $this->errors[__METHOD__] = USER_PASSWORDS_NOT_EQUAL_ERR_MSG;
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
                if ($_SESSION['user']['type'] == ADMIN_CODE) {
                    $_SESSION['filter'] = 'all';
                } else {
                    $_SESSION['filter'] = 'published';
                }
                header('Location: index.php');
            } else {
                $this->errors[__METHOD__] = WRONG_PASSWORD_ERR_MSG;
                $this->messages[__METHOD__] = USER_LOGIN_FAILURE_MSG;
                $block = $this->render(
                    $this->modelName . '/login.php',
                    [$this->itemName => $this->data]
                );
            }
        } else {
            $this->errors[__METHOD__] = LACK_DATA_FOR_LOGIN_ERR_MSG;
            $this->messages[__METHOD__] = USER_LOGIN_FAILURE_MSG;
            $block = $this->render(
                $this->modelName . '/login.php',
                [$this->itemName => $this->data]
            );
        }
        $this->outputTemplate ='users/blank.php';
        $this->messages[__METHOD__] = USER_LOGIN_FAILURE_MSG;
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
        if ($this->getParamLogin('login') &&
            $this->isUserUnique() &&
            $this->getParamMail('email') &&
            $this->checkPasswords()
        ) {
            $this->data['type'] = USER_CODE;
            
            $isRegistered = $this->model->add($this->data);
            if ($isRegistered) {
                $this->userRegistered();
                $this->messages[__METHOD__] = USER_REGISTER_SUCCESS_MSG;
                $block = $this->render(
                    $this->modelName . '/welcome.php',
                    [$this->itemName => $this->data]
                );
            } else {
                $errorInfo = $this->model->getLastPDOError();
                $this->errors[__METHOD__] =
                    USER_REGISTER_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
                $this->messages[__METHOD__] = USER_REGISTER_FAILURE_MSG;
                $block = $this->render(
                    $this->modelName . '/register.php',
                    [$this->itemName => $this->data]
                );
            }
        } else {
            $this->messages[__METHOD__] = USER_REGISTER_FAILURE_MSG;
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
                    header('Location: index.php?c=topics&a=list');
                default: //'Гость'
                    $block = $this->render('topics/list.php');
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
