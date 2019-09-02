<?php

namespace localhost\controller;

//use localhost\controller\PrimaryController;
use localhost\classes\Application;
use localhost\model\Questions;
use localhost\model\Topics;

//require_once 'PrimaryController.php';
//include 'model/questions.php';

/**
 * Class QuestionsController
 * @package localhost\controller
 */
class QuestionsController extends TopicsController
{

    /**
     * @var Topics|null
     */
    private $topics = null;

    /**
     * @var int
     */
    private $curTopicId = -1;

    /**
     * QuestionsController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
 
        $this->intrusionPlaceName = 'question_id';

        $this->setCurrentTopicId();
        
        $this->addItemIntrusionType = 'replace';
        $this->updateItemIntrusionType = 'insert';
        $this->getItemIntrusionType = 'insert';
    }

    /**
     *
     */
    protected function initModels()
    {
        $this->model = new Questions($this);
        $this->topics = new Topics($this);

        $this->modelName = 'questions';
        $this->itemName = 'question';
        $this->datasetName = 'topics';
    }

    /**
     *
     */
    public function setCurrentTopicId()
    {
        if (isset($_GET['topic_id']) && is_numeric($_GET['topic_id'])) {
            $this->curTopicId = $_GET['topic_id'];
        } elseif ($this->currentItemID > 0) {
            $topicId = $this->model->getTopicId($this->currentItemID);
            $errorInfo = $this->model->getLastPDOError();
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
        return ['text' => '', 'topic_id' => $this->curTopicId, 'status' => 0, 'published' => 0];
    }

    /**
     * @return bool
     */
    public function setInputData()
    {
        $this->data['status'] = 0;
        return (
            $this->getParamSimple('text') &&
            $this->getParamNumeric('topic_id') &&
            $this->getParamLogical('published')
        );
    }

    /**
     * @param array $intrusion
     */
    public function renderResultPage($intrusion = []) //renderResultPage
    {
        $this->getQuestionsList($intrusion);
        
        parent::renderResultPage(
            ['topic_id' => $this->curTopicId,
            'block' => $this->outputBlock,
            'type' => 'insert']
        );
    }

    /**
     * @param array $intrusion
     */
    public function getQuestionsList($intrusion = [])
    {
        if ($this->curTopicId != -1) {
            if (isset($_SESSION['user']['type']) &&
                    $_SESSION['user']['type'] == ADMIN_CODE) {
                if (isset($_GET['filter'])) {
                    switch ($_GET['filter']) {
                        case "unanswered":
                            $questionsList = $this->model->getUnansweredList($this->curTopicId);
                            break;
                        case "unpublished":
                            $questionsList = $this->model->getUnpublishedList($this->curTopicId);
                            break;
                    }
                } else {
                    $questionsList = $this->model->getList($this->curTopicId);
                }
            } else {
                $questionsList = $this->model->getPublishedList($this->curTopicId);
            }
            $this->outputBlock = $this->render(
                $this->modelName . '/list.php',
                ['topic_id' => $this->curTopicId,
                'questions' => $questionsList,
                'intrusion' => $intrusion]
            );
        } else {
            $this->errors['topic_id'] = 'Error in Questions topic_id';
            $this->messages['getQuestionsList'] = 'Unable to get Questions list';
        }
    }

    /**
     *
     */
    public function getItem()
    {
        if ($this-> isCurrentItemExist()) {
            $block = $this->render(
                $this->modelName . '/update.php',
                [$this->itemName => $this->getCurrentItem(),
                'topics' => $this->getDataset()]  //'topics' - new
            );
        } else {
            $block = $this->render(
                $this->modelName . '/add.php',
                [$this->itemName => $this->getEmptyItem()]
            );
        }
        
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
            'block' => $block,
            'type' => $this->getItemIntrusionType]
        );
    }

    /**
     *
     */
    public function addItem()
    {
        if (!(isset($_SESSION['user']['email']))) {
            if (isset($_GET['login'])
                    && preg_match('/.{5,}/is', $_GET['login'])
                    && isset($_GET['email'])
                    && preg_match('/.+@.+\..+/is', $_GET['email'])) {
                $data['login'] = $_GET['login'];
                $data['email'] = $_GET['email'];
                $data['type'] = USER_CODE;
                $data['password'] ='';
                $user = $this->users->getUserByLogin($data['login']);
                if ($this->users->isUserUnique($data['login'], -1)
                        && ($user['email'] != $data['email'])) {
                    if ($this->users->add($data)) {
                        $_SESSION['user'] = $this->users->getUserByLogin($data['login']);
                        $this->application->addActionButton('logout');
                    } else {
                        $errorInfo = $this->users->getLastPDOError();
                        $this->errors['addItem'] = 'Item not added. Database Error: ' . $errorInfo['2'];
                    }
                }
            }
        }
        parent::addItem();
    }

    /**
     *
     */
    public function updateItem()
    {
        $block = '';
        if ($this-> isCurrentItemExist() && $this->setInputData()) {
            $isUpdate = $this->model->update($this->currentItemID, $this->data);
            if ($isUpdate) {
                $this->itemUpdated();
                $this->messages['updateItem'] = 'Item updated!';
            } else {
                $errorInfo = $this->model->getLastPDOError()['2'];
                $this->errors['updateItem'] = 'Topic not updated. Database Error: ' . $errorInfo['2'];
                $this->messages['updateItem'] = 'Item not updated!';
                $block = $this->render(
                    $this->modelName . '/update.php',
                    [$this->itemName => $this->currentItem,
                    'topics' => $this->getDataset()]  //'topics' - new
                );
            }
        } else {
            $block = $this->render(
                $this->modelName . '/update.php',
                [$this->itemName => $this->currentItem,
                'topics' => $this->getDataset()]  //'topics' - new
            );
            $this->messages['updateItem'] = 'Item not updated!';
        }
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
            'block' => $block,
            'type' => $this->updateItemIntrusionType]
        );
    }
}
