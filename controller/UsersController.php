<?php

//echo 'UsersController<br/>';
require_once 'PrimaryController.php';
include 'model/users.php';


class UsersController extends PrimaryController
{
    
/*    protected $errors = [];
    private $router;
*/
    function __construct($router)
    {
        parent::__construct($router);
        $this -> intrusionPlaceName = 'user_id';
        $this -> outputTemplate ='users/list.php';
    }

    protected function initModels()
    {
        $this -> model = $this -> users;
        $this -> modelName = 'users';
        $this -> datasetName = 'users';
        $this -> itemName = 'user';
    }

    public function getDataset()
    {
        return $this -> model -> getList();
    }

//////////////////////////////
    public function getCurrentItem()
    {
        return $this -> model -> getItem($this -> currentItemID);
    }


    public function getEmptyItem()
    {
        return ['login' => '', 'password' => '', 'mail' => ''];
    }

/////////////////////////////
    public function setInputData()
    {
        $result = (
            $this -> getParamLogin('login') &&
            $this -> isUserUnique() &&
            $this -> getParamPassword('password') &&
            $this -> getParamMail('mail') &&
            $this -> getParamType('type')
        );
        return $result;      
    }



    public function getParamLogin($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match('/.{5,}/is', $_POST[$name])) {
            $this -> data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this -> errors[$name] = 'Error in user login';
        }
        return $success;
    }

    public function isUserUnique()
    {
        $result = false;
        if (isset($this -> data['login'])) {
            $result = $this -> model -> isUserUnique($this -> data['login'], $this -> currentItemID);
        } else {
            $this -> errors['unique'] = 'User with entered login exist';
        }
        return $result;    
    }

    public function getParamMail($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match('/.+@.+\..+/is', $_POST[$name])) {
            $this -> data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this -> errors[$name] = 'Error in user mail';
        }
        return $success;
    }


    public function getParamPassword($name)
    {
        $success = false;
        if (isset($_POST[$name]) && preg_match('/.{5,}/is', $_POST[$name])) {
            $this -> data[$name] = $_POST[$name];
            $success = true;
        } else {
            $this -> errors[$name] = 'Error in user password';
        }
        return $success;
    }

    public function getParamType($name)
    {
        $success = false;
        if (isset($_POST[$name])) {
            $this -> data[$name] = $_POST[$name];
            $success = true;
        } else {
            $data[$name] = USER_TYPES['Гость'];
            $success = true;
            $this -> messages[$name] = 'Using default user type';
        }
        return $success;
    } 

    public function checkPasswords()
    {
        $result = false;
        if (isset($_POST['password1']) && isset($_POST['password2']) && ($_GET['password1'] === $_GET['password2'])) {
            $this -> data['password'] = $_POST['password1'];
            $result = true; 
        } else {
            $this -> errors['password'] = 'Error in user password';
        }
        return $result;
    }


    public function checkUser()
    {
        $success = false;
        if (isset($_SESSION['user']['type']) &&
            $_SESSION['user']['type'] == USER_TYPES['Администратор']) {
            $success = true;
        } 
        return $success;
    }

/*
    public function updateItem()
    {
        $this -> setCurrentItemId();
        $block = '';
        if ($this-> isCurrentItemExist() && $this -> setInputData()) {
            $isUpdate = $this -> model -> update($this -> currentItemID, $this -> data);
            if ($isUpdate) {
                $this -> itemUpdated();
                $this -> messages['updateItem'] = 'Item updated!';
//              $this -> getUsersList();
            } else {
                $errorInfo = $this -> model -> getLastPDOError()['2'];
                $this -> errors['updateItem'] = 'Item is not updated. Database Error: ' . $errorInfo;
                $this -> messages['updateItem'] = 'Item not updated!';
                $block = $this -> render(
                    $this -> modelName . '/update.php',
                    [$this -> itemName => $this -> currentItem]  
                );
            }
        } else {

            $block = $this -> render(
                $this -> modelName . '/update.php',
                [$this -> itemName => $this -> currentItem]
            );
            $this -> messages['updateItem'] = 'Item not updated!';
        }
        
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => $this -> intrusionPlaceValue, 
            'block' => $block,
            'type' => $this -> updateItemIntrusionType]
        );
    }   
*/     


    public function login()
    {
        echo '$this -> currentItemID' . $this -> currentItemID . '<br>';
        if  ($this -> getParamLogin('login') 
            && $this -> getParamPassword('password')) {
            
            $user = $this -> model -> getUserByLogin($this -> data['login']);
            if ($user['password'] === $this -> data['password']) {
                $_SESSION['user'] = $user;
                header('Location: index.php');
            } else {
                $this -> errors['login'] = 'Password doesn\'t match user password';
                $block = $this -> render(
                    $this -> modelName . '/login.php',
                    [$this -> itemName => $this -> data]
                );
            }
        } else {
            $this -> errors['input'] = 'Bad input';
            $block = $this -> render(
                $this -> modelName . '/login.php',
                [$this -> itemName => $this -> data]
            );
        }
        $this -> outputTemplate ='users/blank.php';
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => '', 
            'block' => $block,
            'type' => '']
        );
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php');
    }


    public function register()
    {
        $block = '';
        if  (
            $this -> getParamLogin('login') &&  
            $this -> isUserUnique() &&         
            $this -> getParamMail('mail') &&
            $this -> checkPasswords()
        ) {
            $this -> data['type'] = USER_TYPES['Пользователь'];
            
            $isRegistered = $this -> model -> add($this -> data);
            if ($isRegistered) {
                $this -> userRegistered();
                $this -> messages['register'] = 'Registration successeful';
                $block = $this -> render(
                     $this -> modelName . '/welcome.php',
                    [$this -> itemName => $this -> data]
                );
            } else {
                $errorInfo = $this -> model -> getLastPDOError()['2'];
                $this -> errors['register'] = 'User is not registered. Database Error: ' . $errorInfo;
                $block = $this -> render(
                     $this -> modelName . '/register.php',
                    [$this -> itemName => $this -> data]
                );
            }
        } else {                
            $block = $this -> render(
                 $this -> modelName . '/register.php',
                [$this -> itemName => $this -> data]
            );
        }
        $this -> outputTemplate ='users/blank.php';
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => '', 
            'block' => $block,
            'type' => '']
        );
    }


    public function userRegistered()
    {

    } 


    public function welcome()
    {
        $block = '';
        if (isset($_SESSION['user']['type'])) {
            switch ($_SESSION['user']['type']) {
                case USER_TYPES['Администратор']:
                    $block =  $this -> render('users/admin.php');
                    break;
                case USER_TYPES['Пользователь']:
                    $block =  $this -> render('topics/list.php');
                    break;
                default: //'Гость'
                    $block =  $this -> render('topics/list.php');
                    break;
            }
        } else {
            $block =  $this -> render('users/welcome.php');
        }
        $this -> outputTemplate ='users/blank.php';
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => '', 
            'block' => $block,
            'type' => '']
        );   
    }

    public function gotoLogin()
    {
        $block = $this -> render('users/login.php');        $this -> outputTemplate ='users/blank.php';
        $this -> outputTemplate ='users/blank.php';
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => '', 
            'block' => $block,
            'type' => '']
        );
    }

    public function gotoRegister()
    {
        $block =  $this -> render('users/register.php');
        $this -> outputTemplate ='users/blank.php';
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => '', 
            'block' => $block,
            'type' => '']
        );
    }
    

}