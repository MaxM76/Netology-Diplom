<?php  

/*
Question
-question_id
-topic_id
-text
-user_id
-date
status (hidden, nonanswered, answered)
addQuestion(text, topic_id) user_id, date automatically
deleteQuestion(question_id)
updateQuestion(question_id, text)
changeTopic(question_id, topic_id)
changeStatus
*/
class Question
{
    private $id = -1;
    private $text = '';
    private $date;
    private $status = 0; // nonanswered, answered
    private $published = 0; // hidden, published
    private $author = -1;
    private $topic = -1;
    private $questions;
    private $changedFields =[];

    public function __construct($questions)
    {
        $this -> questions = $questions;
    }

    function add()
    {
        $this -> setAuthor();
        return $this -> getQuestions() -> addQuestion($this);
    }

    function setAuthor()
    {
        $this -> author = $_SESSION['user'];
    }

    function setText($text)
    {
        if ($this -> text !== $text) {
            $this -> text = $text;
            array_push($this -> changedFields, 'text');
            $this -> getQuestions() -> update($this -> id, $this, ['text']);
        }
    }

    function setTopic($topic)
    {
        if ($this -> topic !== $topic) {
            $this -> topic = $topic;
            array_push($this -> changedFields, 'topic');
            $this -> getQuestions() -> update($this -> id, $this, ['topic']);
        }       
    }

    function setStatus($status)
    {
        if (($this -> status !== $status) && ($status === 1)) {
            $this -> status = $status;
            array_push($this -> changedFields, 'is_answered');
            $this -> getQuestions() -> update($this -> id, $this, ['is_answered']);
        }      
    }

    function setPublished($published)
    {
        if ($this -> $published !== $published) {
            $this -> $published = $published;
            array_push($this -> changedFields, 'is_published');
            $this -> getQuestions() -> update($this -> id, $this, ['is_published']);
        }
    }


    function getText()
    {
        return $this -> text;
    }

    function getTopic()
    {
        return $this -> topic;
    }

    function getStatus()
    {
        return $this -> status;
    }

    function getDate()
    {
        return $this -> date;
    }

    function getAuthor()
    {
        return $this -> author;
    }

    function getPublished()
    {
        return $this -> published;
    }

    function getQuestions()
    {
        return $this -> questions;
    }

    function update()
    {

    }

/*
    function delete($id)
    {
        $this -> getQuestions() -> deleteQuestion($this -> id);
    }
*/

    function answer()
    {
        $this -> setStatus(1);
    }

    function publish()
    {
        $this -> setPublished(1);
    }

    function hide()
    {
        $this -> setPublished(0);
    }

}



class Questions
{
    private $controller;

    function __construct($controller)
    {
        $this -> controller = $controller;
    }


    public function getDatabase()
    {
        return $this -> controller -> getRouter() -> getDatabase();
    }

    function addQuestion($question)
    {
        $sth = $this -> getDatabase() -> prepare(
            'INSERT INTO `questions` (text, topic, date, author, status)'.
            ' VALUES (:text, :topic, :date, :author, :status)'
        );

        $sth -> bindValue(':text', $question -> getText(), PDO::PARAM_STR);
        $sth -> bindValue(':topic', $question -> getTopic(), PDO::PARAM_INT);
        $sth -> bindValue(':date', $question -> getDate(), PDO::PARAM_STR);
        $sth -> bindValue(':author', $question -> getAuthor(), PDO::PARAM_INT);
        $sth -> bindValue(':status', $question -> getStatus(), PDO::PARAM_STR);

        $result = $sth -> execute();

        return $result;
    }

    function deleteQuestion($id)
    {
        $sth = $this -> getDatabase() -> prepare('DELETE FROM `questions` WHERE id=:id');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $result = $sth -> execute();
        if ($result) {
            $this -> updateStatistics();
        }    
        return $result;
    }

    function updateQuestion($id, $question, $fields) //text, status, topic
    {
        if (count($fields) == 0) {
            return false;
        }
        $update = [];
        foreach ($fields as $field) {
            $update[] = $field.'`=:'.$field;
        }

        $sth = $this -> getDatabase() -> prepare('UPDATE `questions` SET `'.implode(', `', $update).' WHERE `id`=:id');

        foreach ($fields as $field) {
            $getter = 'get'.$field;
            $sth -> bindValue(':$field', $question -> $getter(), PDO::PARAM_STR);
        }
    
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);

        return $sth->execute();
    }

    function getQuestion($id)
    {
        $sth = $this -> getDatabase() -> prepare('SELECT `id`, `text`, `topic`, `date`, `author`, `status` FROM `questions` WHERE `id`=:id');
        $sth -> bindValue(':id', $id, PDO::PARAM_INT);
        $sth -> execute();
        $result = $sth -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function getQuestionsList($topicId)
    {
        $sth = $this -> getDatabase() -> prepare('SELECT `id`, `text`, `topic`, `date`, `author`, `status` FROM `questions` WHERE `topic_id`=:id');
        $sth -> bindValue(':id', $topicId, PDO::PARAM_INT);
        if ($sth -> execute()) {
            return $sth -> fetchAll();
        }
        return false;
    }
}