<?php

class Topics
{

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

    public function add($params)
    {
        $sth = $this -> getDatabase() -> prepare('
                INSERT
                INTO
                    `topics` (`text`, `description`, `author`)
                VALUES
                    (:text, :description, :author)
                ');
        $sth -> bindValue(':text', $params['text'], PDO::PARAM_STR);
        $sth -> bindValue(':description', $params['description'], PDO::PARAM_STR);
        $sth -> bindValue(':author', $params['author'], PDO::PARAM_INT);
        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    /*
	удаление темы из базы данных
	*/

    public function deleteQuestionsOfTopic($id)
    {
        $sth = $this -> getDatabase() -> prepare('
                SELECT
                    COUNT(*) AS `total`
                FROM
                    `questions`
                WHERE
                    `topic_id`=:id
                ');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        if ($sth -> execute()) {
            $result = $sth -> fetch(PDO::FETCH_ASSOC);
        } else {
            $this -> lastPDOError = $sth -> errorInfo();
            return false;
        }

        if ($result['total'] == 0) {
            return true;
        }

        $sth = $this -> getDatabase() -> prepare('
                SELECT
                    `question_id`
                FROM
                    `questions` 
                WHERE
                    `topic_id`=:id
                ');

        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();

        if ($result) {
            $questionsList = $sth -> fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }

        if ($questionsList) {
            foreach ($questionsList as $question) {
                $result = $result && $this -> deleteQuestion($question['question_id']);
            }
        }

        return $result;
    }

    public function deleteQuestion($questionId)
    {        
        $result = true;
        if ($this -> deleteAnswersOfQuestion($questionId)) {
            $sth = $this -> getDatabase() -> prepare('
                DELETE
                FROM
                    `questions`
                WHERE
                    `question_id`=:id
                ');
            $sth -> bindValue(':id', $questionId, PDO::PARAM_INT);
            $result = $sth -> execute();
            $this -> lastPDOError = $sth -> errorInfo();
        } else {
            $result = false;
        }
        return $result;
    }

    public function deleteAnswersOfQuestion($questionId)
    {
        $sth = $this -> getDatabase() -> prepare('
                SELECT
                    COUNT(*) AS `total`
                FROM
                    `answers`
                WHERE
                    `question_id`=:id
                ');
        $sth -> bindValue(':id', $questionId, PDO::PARAM_INT);
        if ($sth -> execute()) {
            $result = $sth -> fetch(PDO::FETCH_ASSOC);
        } else {
            $this -> lastPDOError = $sth -> errorInfo();
            return false;
        }

        if ($result['total'] == 0) {
            return true;
        }

        $sth = $this -> getDatabase() -> prepare('
                DELETE
                FROM
                    `answers`
                WHERE
                    `question_id`=:id
                ');
        $sth -> bindValue(':id', $questionId, PDO::PARAM_INT);
        $success = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $success;
    }

    public function delete($id)
    {
        if (!($this -> deleteQuestionsOfTopic($id))) {
            return false;
        }

        $sth = $this -> getDatabase() -> prepare(
                'DELETE
                 FROM
                     `topics`
                 WHERE
                     `topic_id`=:id
                ');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }


    public function update($id, $params)
    {
        if (count($params) == 0) {
            return false;
        }
        $update = [];
        foreach ($params as $param => $value) {
            $update[] = $param.'`=:'.$param;
        }

        $sth = $this -> getDatabase() -> prepare('
                UPDATE
                    `topics`
                SET
                    `'.implode(', `', $update).'
                WHERE
                    `topic_id`=:id'
                );

        if (isset($params['text'])) {
            $sth->bindValue(':text', $params['text'], PDO::PARAM_STR);
        }

        if (isset($params['description'])) {
            $sth->bindValue(':description', $params['description'], PDO::PARAM_STR);
        }
        
        $sth->bindValue(':id', $id, PDO::PARAM_INT);

        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }


    public function getList()
    {
        $sth = $this -> getDatabase() -> prepare('
            SELECT
                `topics`.`topic_id`,
                `topics`.`text`,
                `topics`.`description`,
                `topics`.`date`,
                COUNT(*) AS `total`,
                SUM(`questions`.`published`) AS `published`,
                SUM(`questions`.`status`) AS `answered`
            FROM
                `topics`,
                `questions`
            WHERE
                `questions`.`topic_id` = `topics`.`topic_id`
            GROUP BY
                `topics`.`topic_id`
            UNION 
            SELECT 
                `topic_id`,
                `text`,
                `description`,
                `date`,
                0 AS `total`,
                0 AS `published`,
                0 AS `answered`
            FROM
                `topics`
            WHERE
                `topic_id` NOT IN (
                SELECT
                    `topic_id`
                FROM
                    `questions`
                )
            ');

        if ($sth -> execute()) {
            $result = $sth -> fetchAll();
            // print_r($result);
            // echo 'all<br/>';
        } else {
            $result = false;    
            // print_r($result);
            // echo 'false<br/>';
        }
        $this -> lastPDOError = $sth -> errorInfo();
        // print_r($this -> lastPDOError);
        // echo '<br/>';
        return $result;
    }

    public function getItem($id)
    {

        $sth = $this -> getDatabase() -> prepare('
                SELECT
                    `topic_id`, `text`, `description`, `date`
                FROM
                    `topics`
                WHERE
                    `topic_id`=:id
                ');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

}
