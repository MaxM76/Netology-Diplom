<?php

namespace lh\model;

/**
 * Class Answers
 * @package lh\model
 */
class Answers extends Model
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
                answers (`question_id`, `text`, `author_id`)
            VALUES
                (:question_id, :text, :author)
        ');

        $sth->bindValue(':question_id', $params['question_id'], \PDO::PARAM_INT);
        $sth->bindValue(':text', $params['text'], \PDO::PARAM_STR);
        $sth->bindValue(':author', $params['author'], \PDO::PARAM_INT);

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
                `answers`
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
                `answers`
            SET '
                .implode(', `', $update).'
            WHERE
                `id`=:id
        ');

        if (isset($params['text'])) {
            $sth->bindValue(':text', $params['text'], \PDO::PARAM_STR);
        }

        if (isset($params['author'])) {
            $sth->bindValue(':author', $params['author'], \PDO::PARAM_STR);
        }

        if (isset($params['question_id'])) {
            $sth->bindValue(':question_id', $params['question_id'], \PDO::PARAM_INT);
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
    public function getList($ownerId = -1)
    {
        if ($ownerId == -1) {
            return [];
        }
        $sth = $this->getDatabase()->prepare(
            'SELECT
                `answers`.`id`,
                `question_id`,
                `text`,
                `created_at`,
                `author_id`,
                `login`,
                `email`
            FROM
                `answers`
            INNER JOIN
                `users`
            ON
                `author_id`=`users`.`id`
            WHERE
                `question_id`=:id'
        );

        $sth->bindValue(':id', $ownerId, \PDO::PARAM_INT);
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
                `id`,
                `question_id`,
                `text`,
                `created_at`,
                `author_id`
            FROM
                `answers` 
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
     * @param $id
     * @return int
     */
    public function getQuestionId($id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `question_id`
            FROM
                `answers`
            WHERE
                `id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        return $result['question_id'];
    }
}
