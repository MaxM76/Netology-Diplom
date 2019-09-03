<?php

namespace lh\controller;

use lh\classes\Application;
use lh\model\Answers;
use lh\model\Questions;
use lh\model\Topics;

/**
 * Class AnswersController
 * @package lh\controller
 */
class AnswersController extends TopicsController
{
    /**
     * @var Questions|null
     */
    protected $questions = null;

    /**
     * @var Topics|null
     */
    protected $topics = null;

    /**
     * @var int
     */
    protected $curTopicId = -1;

    /**
     * @var int
     */
    protected $curQuestionId = -1;

    /**
     * AnswersController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->intrusionPlaceName = 'question_id';
        $this->setCurrentQuestionID();
        $this->setCurrentTopicId();
        $this->addItemIntrusionType = 'insert';
        $this->updateItemIntrusionType = 'insert';
    }

    /**
     *
     */
    protected function initModels()
    {
        $this->model = new Answers($this);
        $this->questions = new Questions($this);
        $this->topics = new Topics($this);
        $this->modelName = 'answers';
        $this->itemName = 'answer';
        $this->datasetName = 'topics';
    }

    /**
     * @return bool
     */
    public function setCurrentItemId()
    {
        $result = false;
        if ((count($_GET) > 0) && isset($_GET['answer_id']) && is_numeric($_GET['answer_id'])) {
            $this->currentItemID = $_GET['answer_id'];
            $result = true;
        }
        return $result;
    }

    /**
     *
     */
    public function setCurrentQuestionID()
    {
        if (isset($_GET['question_id']) && is_numeric($_GET['question_id'])) {
            $this->curQuestionId = $_GET['question_id'];
            $this->setIntrusionPlaceValue($this->curQuestionId);
            $this->data['question_id'] = $this->curQuestionId;
        } elseif ($this->currentItemID > 0) {
            $questionId = $this->model->getQuestionId($this->currentItemID);
            $errorInfo = $this->model->getLastPDOError();
            if ($errorInfo['0'] != 0) {
                $this->errors['question_id'] = 'No question_id Info. Database Error: ' . $errorInfo['2'];
            } else {
                $this->curQuestionId = $questionId;
                $this->setIntrusionPlaceValue($this->curQuestionId);
                $this->data['question_id'] = $this->curQuestionId;
            }
        } else {
            $this->errors['question_id'] = 'Error in getting question_id';
        }
    }

    /**
     *
     */
    public function setCurrentTopicID()
    {
        if (isset($_GET['topic_id']) && is_numeric($_GET['topic_id'])) {
            $this->curTopicId = $_GET['topic_id'];
        } elseif ($this->curQuestionId > 0) {
            $topicId = $this->questions->getTopicId($this->curQuestionId);
            $errorInfo = $this->questions->getLastPDOError();
            if ($errorInfo['0'] != 0) {
                $this->errors['topic_id'] = 'No topic_id Info. Database Error: ' . $errorInfo['2'];
            } else {
                $this->curTopicId = $topicId;
            }
        } else {
            $this->errors['topic_id'] = 'Error in getting topic_id';
        }
    }

    /**
     *
     */
    public function getItem()
    {
        if ($this->isCurrentItemExist()) {
            $block = $this->render(
                $this->modelName . '/update.php',
                [$this->itemName => $this->getCurrentItem()]
            );
        } else {
            $block = $this->render(
                $this->modelName . '/add.php',
                [$this->itemName => $this->getEmptyItem(),'question' => $this->getCurrentQuestion()]
            );
        }
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
                'block' => $block,
                'type' => $this->getItemIntrusionType]
        );
    }

    /**
     * @return array
     */
    public function getDataset()
    {
        return $this->topics->getList();
    }

    /**
     * @return array
     */
    public function getEmptyItem()
    {
        return ['question_id' => $this->curQuestionId, 'text' => ''];
    }

    /**
     * @return bool
     */
    public function setInputData()
    {
        return ($this->getParamSimple('text'));
    }

    /**
     * @param array $intrusion
     */
    public function renderResultPage($intrusion = [])
    {
        $this->getAnswersList($intrusion);

        $this->outputBlock = $this->render(
            'questions/list.php',
            [
                'questions' => $this->questions->getList($this->curTopicId),
                'intrusion' =>
                [
                    'question_id' => $this->curQuestionId,
                    'block' => $this->outputBlock,
                    'type' => 'insert'
                ]
            ]
        );
        parent::renderResultPage(
            ['topic_id' => $this->curTopicId,
            'block' => $this->outputBlock,
            'type' => 'replace']
        );
    }

    /**
     * @param array $intrusion
     */
    public function getAnswersList($intrusion = [])
    {
        if ($this->curQuestionId != -1) {
            $this->outputBlock = $this->render(
                $this->modelName . '/list.php',
                ['question' => $this->questions->getItem($this->curQuestionId),
                'topic_id' => $this->curTopicId,
                'answers' => $this->model->getList($this->curQuestionId),
                'intrusion' => $intrusion]
            );
        } else {
            $this->errors['getAnswersList'] = 'Error in Answers question_id';
            $this->messages['getAnswersList'] = 'Unable to get Answers list';
        }
    }

    /**
     * @return array
     */
    protected function getCurrentQuestion()
    {
        return $this->questions->getItem($this->curQuestionId);
    }

    /**
     *
     */
    public function itemDeleted()
    {
        if ($this->questions->getAnswersCount($this->curQuestionId) == 0) {
            $this->questions->unanswerQuestion($this->curQuestionId);
        }
    }

    /**
     *
     */
    public function itemAdded()
    {
        $this->questions->answerQuestion($this->curQuestionId);
        if (isset($_GET['publish']) && ($_GET['publish'] == 'true')) {
            $published = QUESTION_PUBLISHED;
        } else {
            $published = QUESTION_NOT_PUBLISHED;
        }
        if ($published != $this->getCurrentQuestion()['is_published']) {
            if ($published) {
                $this->questions->publishQuestion($this->curQuestionId);
            } else {
                $this->questions->unpublishQuestion($this->curQuestionId);
            }
        }
    }

    /**
     *
     */
    public function updateItem()
    {
        if ($this->checkUser()) {
            parent::updateItem();
        }
    }
}
