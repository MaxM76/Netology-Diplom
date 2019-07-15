<?php  

class Questions
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



    function add($params)
    {
        $sth = $this -> getDatabase() -> prepare('
                INSERT
                INTO
                    `questions` (`text`, `topic_id`, `author`, `status`, `published`)
                VALUES
                    (:text, :topic_id, :author, :status, :published)
                ');

        $sth -> bindValue(':text', $params['text'], PDO::PARAM_STR);
        $sth -> bindValue(':topic_id', $params['topic_id'], PDO::PARAM_INT);
        $sth -> bindValue(':author', $params['author'], PDO::PARAM_INT);
        $sth -> bindValue(':status', $params['status'], PDO::PARAM_INT);
        $sth -> bindValue(':published', $params['published'], PDO::PARAM_INT);

        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    public function deleteAnswersOfQuestion($id)
    {
        $sth = $this -> getDatabase() -> prepare('
                SELECT
                    COUNT(*) AS `total`
                FROM
                    `answers`
                WHERE
                    `question_id`=:id
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
                DELETE
                FROM
                    `answers`
                WHERE
                    `question_id`=:id
                ');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $success = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $success;       
    }

    function delete($id)
    {
        if ($this -> deleteAnswersOfQuestion($id)) {
            $sth = $this -> getDatabase() -> prepare('
                DELETE
                FROM
                    `questions`
                WHERE
                    `question_id`=:id
                ');
            $sth -> bindValue(':id', $id, PDO::PARAM_INT);
            $result = $sth -> execute();
            $this -> lastPDOError = $sth -> errorInfo();
            return $result;
        } else {
            return false;
        }

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

        $sth = $this -> getDatabase() -> prepare('
                UPDATE
                    `questions`
                SET
                    `'.implode(', `', $update).'
                WHERE
                    `question_id`=:id
            ');

        if (isset($params['text'])) {
            $sth->bindValue(':text', $params['text'], PDO::PARAM_STR);
        }

        if (isset($params['topic_id'])) {
            $sth->bindValue(':topic_id', $params['topic_id'], PDO::PARAM_INT);
        }

        if (isset($params['published'])) {
            $sth->bindValue(':published', $params['published'], PDO::PARAM_INT);
        }

        if (isset($params['status'])) {
            $sth->bindValue(':status', $params['status'], PDO::PARAM_INT);
        }
    
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);

        $result = $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;

    }

    function getItem($id)
    {
        $sth = $this -> getDatabase() -> prepare('
                SELECT
                    `question_id`, `text`, `topic_id`, `date`, `author`, `status`, `published`
                FROM
                    `questions`
                WHERE
                    `question_id`=:id
                ');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function getList($topicId)
    {
        $sth = $this -> getDatabase() -> prepare('
            SELECT
                `question_id`,
                `text`,
                `topic_id`,
                `date`,
                `author`,
                `login`,
                `mail`,
                `status`,
                `published`
            FROM
                `questions` 
            INNER JOIN
                `faqusers`
            ON
                `author`=`user_id`    
            WHERE
                `topic_id`=:id
            ');

        //SELECT `question_id`, `text`, `topic_id`, `date`, `author`, `login`, `mail`, `status`, `published` FROM `questions` INNER JOIN `faqusers` ON `author`=`user_id` WHERE `topic_id`=1

        $sth -> bindValue(':id', $topicId, PDO::PARAM_INT);
        if ($sth -> execute()) {
            $result = $sth -> fetchAll();
        } else {
            $result = false;    
        }
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    function getPublishedList($topicId)
    {
        $sth = $this -> getDatabase() -> prepare('
            SELECT
                `question_id`,
                `text`,
                `topic_id`,
                `date`,
                `author`,
                `login`,
                `mail`,
                `status`,
                `published`
            FROM
                `questions` 
            INNER JOIN
                `faqusers`
            ON
                `author`=`user_id`    
            WHERE
                `topic_id`=:id AND `published`= true
            ');


        $sth -> bindValue(':id', $topicId, PDO::PARAM_INT);
        if ($sth -> execute()) {
            $result = $sth -> fetchAll();
        } else {
            $result = false;    
        }
        $this -> lastPDOError = $sth -> errorInfo();
        return $result;
    }

    public function getTopicId($id)
    {
        $sth = $this -> getDatabase() -> prepare('
            SELECT
               `topic_id`
            FROM
                `questions`
            WHERE
                `question_id`=:id
            ');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $this -> lastPDOError = $sth -> errorInfo();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        return $result['topic_id'];        
    }



    public function answerQuestion($id)
    {
        return $this -> update($id, ['status' => 1]);
    }

        public function unanswerQuestion($id)
    {
        return $this -> update($id, ['status' => 0]);        
    }


}