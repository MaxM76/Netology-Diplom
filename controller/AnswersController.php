<?php

require_once 'PrimaryController.php';
include 'model/answers.php';

class AnswersController extends TopicsController 
{

    protected $questions = null;
    protected $topics = null;
    protected $curTopicId = -1;
    protected $curQuestionId = -1;

    function __construct($router)
    {
        parent::__construct($router);        

        $this -> intrusionPlaceName = 'question_id';

        $this -> setCurrentQuestionID();
        $this -> setCurrentTopicId();

        $this -> addItemIntrusionType = 'insert';
        $this -> updateItemIntrusionType = 'insert';
    }

    protected function initModels()
    {
        $this -> model = new Answers($this);
        $this -> questions = new Questions($this);
        $this -> topics = new Topics($this);
        $this -> modelName = 'answers';
        $this -> itemName = 'answer';
        $this -> datasetName = 'topics';
    }


    public function setCurrentItemId()
    {
        $result = false;
        if ((count($_GET) > 0) && isset($_GET['answer_id']) && is_numeric($_GET['answer_id'])) {
            $this -> currentItemID = $_GET['answer_id'];
            $result = true;
        }
        return $result;
    }

    public function setCurrentQuestionID()
    {
        if (isset($_GET['question_id']) && is_numeric($_GET['question_id'])) {
            $this -> curQuestionId = $_GET['question_id'];
            $this -> setIntrusionPlaceValue($this -> curQuestionId);
            $this -> data['question_id'] = $this -> curQuestionId;
        } elseif ($this -> currentItemID > 0) {
            $questionId = $this -> model -> getQuestionId($this -> currentItemID);
            $errorInfo = $this -> model -> getLastPDOError();
            if ($errorInfo['0'] != 0) {
                $this -> errors['question_id'] = 'No question_id Info. Database Error: ' . $errorInfo['2'];
            } else {
                $this -> curQuestionId = $questionId;
                $this -> setIntrusionPlaceValue($this -> curQuestionId);
                $this -> data['question_id'] = $this -> curQuestionId;
            }
        } else {
            $this -> errors['question_id'] = 'Error in getting question_id';
        }
    }


    public function setCurrentTopicID()
    {
        if (isset($_GET['topic_id']) && is_numeric($_GET['topic_id'])) {
            $this -> curTopicId = $_GET['topic_id'];
        } elseif ($this -> curQuestionId > 0) {
            $topicId = $this -> questions -> getTopicId($this -> curQuestionId);
            $errorInfo = $this -> questions -> getLastPDOError();
            if ($errorInfo['0'] != 0) {
                $this -> errors['topic_id'] = 'No topic_id Info. Database Error: ' . $errorInfo['2'];
            } else {
                $this -> curTopicId = $topicId;
            }
        } else {
            $this -> errors['topic_id'] = 'Error in getting topic_id';
        }
    }


    public function getDataset()
    {
        return $this -> topics -> getList();
    }


    public function getEmptyItem()
    {
        return ['question_id' => $this -> curQuestionId, 'text' => ''];
    }


    public function setInputData()
    {
        return ($this -> getParamSimple('text'));
    }


    public function renderResultPage($intrusion = [])
    {
        $this -> getAnswersList($intrusion);

        $this -> outputBlock = $this -> render(
            'questions/list.php',
            [
                'questions' => $this -> questions -> getList($this -> curTopicId),
                'intrusion' => 
                [
                    'question_id' => $this -> curQuestionId,
                    'block' => $this -> outputBlock,
                    'type' => 'insert'
                ]
            ]
         );

        parent::renderResultPage(
            ['topic_id' => $this -> curTopicId,
            'block' => $this -> outputBlock,
            'type' => 'replace']
        );
    }

    public function getAnswersList($intrusion = [])
    {
        if ($this -> curQuestionId != -1) {
            $this -> outputBlock = $this -> render(
                $this -> modelName . '/list.php',
                ['question' => $this -> questions -> getItem($this -> curQuestionId),
                'topic_id' => $this -> curTopicId,
                'answers' => $this -> model -> getList($this -> curQuestionId),
                'intrusion' => $intrusion]
            );
        } else {
            $this -> errors['getAnswersList'] = 'Error in Answers question_id';
            $this -> messages['getAnswersList'] = 'Unable to get Answers list';
        }
    }



    public function itemDeleted()
    {
        $this -> questions -> unanswerQuestion($_GET['question_id']);
    }



    public function itemAdded()
    {
        $this -> questions -> answerQuestion($this -> curQuestionId);
    }

    public function updateItem()
    {
        if ($this -> checkUser()) {
            parent::updateItem();
        }
    }

}