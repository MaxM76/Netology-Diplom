<?php

class Users
{
    /*
    ------------
    table users
    ------------
    user_id
    login
    password
    type : admin, user, quest
    mail
    */
    private $controller;
    private $lastPDOError = [];

    function __construct($controller)
    {
        $this -> controller = $controller;
    }


    public function getDatabase()
    {
        return $this -> controller -> getRouter() -> getDatabase();

    }

    public function getDatabaseError()
    {
        return $this -> getDatabase() -> errorInfo();
    }

    public function getLastPDOError()
    {
        return $this -> lastPDOError;
    }

    function add($params)
    {
        $sth = $this -> getDatabase() -> prepare(
            'INSERT INTO faqusers (login, password, type, mail)'.
            ' VALUES (:login, :password, :type, :mail)'
        );
        $sth -> bindValue(':login', $params['login'], PDO::PARAM_STR);
        $sth -> bindValue(':password', $params['password'], PDO::PARAM_STR);
        $sth -> bindValue(':type', $params['type'], PDO::PARAM_STR);
        $sth -> bindValue(':mail', $params['mail'], PDO::PARAM_STR);

        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    /*
	удаление из базы данных
	*/
    function delete($id)
    {
        $sth = $this -> getDatabase() -> prepare(
            'DELETE FROM `faqusers` WHERE user_id=:id'
        );
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    function update($id, $params)
    {
        if (count($params) == 0) {
            return false;
        }
        $update = [];
        foreach ($params as $param => $value) {
            $update[] = $param.'`=:'.$param;
        }

        $sth = $this -> getDatabase() -> prepare(
            'UPDATE `faqusers` SET `'.implode(', `', $update).' WHERE `user_id`=:id'
        );

        if (isset($params['login'])) {
            $sth->bindValue(':login', $params['login'], PDO::PARAM_STR);
        }

        if (isset($params['password'])) {
            $sth->bindValue(':password', $params['password'], PDO::PARAM_STR);
        }

        if (isset($params['type'])) {
            $sth->bindValue(':type', $params['type'], PDO::PARAM_STR);
        }

        if (isset($params['mail'])) {
            $sth->bindValue(':mail', $params['mail'], PDO::PARAM_STR);
        }

        $sth->bindValue(':id', $id, PDO::PARAM_INT);

        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }


    public function getList()
    {
        $sth = $this -> getDatabase() -> prepare(
            'SELECT `user_id`, `login`, `password`, `type`, `mail` FROM `faqusers`'
        );

        if ($sth -> execute()) {
            $result = $sth -> fetchAll();
        } else {
            $result = false;
        }
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    public function getItem($id)
    {
        $sth = $this -> getDatabase() -> prepare('SELECT `user_id`, `login`, `password`, `type`,  `mail` FROM `faqusers` WHERE `user_id`=:id');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    public function getUserByLogin($login)
    {
        $sth = $this -> getDatabase() -> prepare('SELECT `user_id`, `login`, `password`, `type`, `mail` FROM `faqusers` WHERE `login`=:login');
        $sth -> bindValue(':login', $login, PDO::PARAM_STR);
        $sth -> execute();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    public function isUserUnique($login, $userId)
    {
        $sth = $this -> getDatabase() -> prepare('SELECT COUNT(*) FROM `faqusers` WHERE `login`=:login and `user_id`!=:user_id');
        $sth -> bindValue(':login', $login, PDO::PARAM_STR);
        $sth -> bindValue(':user_id', $userId, PDO::PARAM_STR);
        $sth -> execute();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        $this -> lastPDOError = $sth -> errorInfo();
        return ($result['COUNT(*)'] == 0);        
    }

}
