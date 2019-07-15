<?php

class Answers
{
    /*
    ------------
    table answers
    ------------
    answer_id
    question_id
    text
    date
    author
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
            'INSERT INTO answers (question_id, text, author)'.
            ' VALUES (:question_id, :text, :author)'
        );
        $sth -> bindValue(':question_id', $params['question_id'], PDO::PARAM_INT);
        $sth -> bindValue(':text', $params['text'], PDO::PARAM_STR);
        $sth -> bindValue(':author', $params['author'], PDO::PARAM_INT);

        $result = $sth -> execute();

        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }


    function delete($id)
    {
        $sth = $this -> getDatabase() -> prepare(
            'DELETE FROM `answers` WHERE answer_id=:id'
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
            'UPDATE `answers` SET `'.implode(', `', $update).' WHERE `answer_id`=:id'
        );

        if (isset($params['text'])) {
            $sth->bindValue(':text', $params['text'], PDO::PARAM_STR);
        }

        if (isset($params['author'])) {
            $sth->bindValue(':author', $params['author'], PDO::PARAM_STR);
        }

         if (isset($params['question_id'])) {
            $sth->bindValue(':question_id', $params['question_id'], PDO::PARAM_INT);
        }       

        $sth->bindValue(':id', $id, PDO::PARAM_INT);

        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }


    public function getList($id)
    {
        $sth = $this -> getDatabase() -> prepare(
            'SELECT
                `answer_id`,
                `question_id`,
                `text`,
                `date`,
                `author`,
                `login`,
                `mail`
            FROM
                `answers`
            INNER JOIN
                `faqusers`
            ON
                `author`=`user_id`                                
            WHERE
                `question_id`=:id'
        );

        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
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
        $sth = $this -> getDatabase() -> prepare(
            'SELECT `answer_id`, `question_id`, `text`, `date`, `author` FROM `answers` 
            WHERE `answer_id`=:id'
            );
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }


    public function getQuestionId($id)
    {
        $sth = $this -> getDatabase() -> prepare(
            'SELECT `question_id`'.
            ' FROM `answers` WHERE `answer_id`=:id'
            );
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        return $result['question_id'];        
    }
}
