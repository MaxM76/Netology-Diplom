<?php

namespace lh\model;

//use lh\controller\PrimaryController;

/**
 * Class Users
 * @package lh\model
 */
class Users extends Model
{
    /**
     * @param array $params
     * @return bool
     */
    public function add($params)
    {
        $sth = $this->getDatabase()->prepare('
            INSERT
            INTO
                `users` (`login`, `password`, `type`, `email`)
            VALUES
                (:login, :password, :type, :email)
        ');
        $sth->bindValue(':login', $params['login'], \PDO::PARAM_STR);
        $sth->bindValue(':password', $params['password'], \PDO::PARAM_STR);
        $sth->bindValue(':type', $params['type'], \PDO::PARAM_STR);
        $sth->bindValue(':email', $params['email'], \PDO::PARAM_STR);

        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }


    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $sth = $this->getDatabase()->prepare('
            DELETE
            FROM
                `users`
            WHERE
                id=:id
        ');
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update($id, $params)
    {
        if (count($params) == 0) {
            return false;
        }
        $update = [];
        foreach ($params as $param => $value) {
            $update[] = $param.'`=:'.$param;
        }

        $sth = $this->getDatabase()->prepare('
            UPDATE
                `users`
            SET `'
                .implode(', `', $update).'
            WHERE
                `id`=:id
        ');

        if (isset($params['login'])) {
            $sth->bindValue(':login', $params['login'], \PDO::PARAM_STR);
        }

        if (isset($params['password'])) {
            $sth->bindValue(':password', $params['password'], \PDO::PARAM_STR);
        }

        if (isset($params['type'])) {
            $sth->bindValue(':type', $params['type'], \PDO::PARAM_STR);
        }

        if (isset($params['email'])) {
            $sth->bindValue(':email', $params['email'], \PDO::PARAM_STR);
        }

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);

        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }


    /**
     * @param int $ownerId
     * @return array
     */
    public function getList($ownerId = UNKNOWN_ITEM_ID)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `id`, `login`, `password`, `type`, `email`
            FROM
                `users`
        ');

        if ($sth->execute()) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getItem($id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `id`, `login`, `password`, `type`, `email`
            FROM
                `users`
            WHERE
                `id`=:id
        ');
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param string $login
     * @return array
     */
    public function getUserByLogin($login)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `id`, `login`, `password`, `type`, `email`
            FROM
                `users`
            WHERE
                `login`=:login
        ');
        $sth->bindValue(':login', $login, \PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param string $login
     * @param int $id
     * @return bool
     */
    public function isUserUnique($login, $id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                COUNT(*)
            FROM
                `users`
            WHERE
                `login`=:login and `id`!=:id
         ');
        $sth->bindValue(':login', $login, \PDO::PARAM_STR);
        $sth->bindValue(':id', $id, \PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        $this->lastPDOError = $sth->errorInfo();
        return ($result['COUNT(*)'] == 0);
    }
}
