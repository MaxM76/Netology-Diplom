<?php

namespace lh\controller;

use lh\classes\Application;
use lh\model\Questions;
use lh\model\Topics;

/**
 * Class QuestionsController
 * @package lh\controller
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
    private $curTopicId = UNKNOWN_ITEM_ID;

    /**
     * QuestionsController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->intrusionPlaceName = 'question_id';
        $this->setCurrentTopicId();
        // $this->addItemIntrusionType = 'replace';
        $this->updateItemIntrusionType = 'insert';
        $this->getItemIntrusionType = 'replace';
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
            if ($errorInfo[PDO_ERROR_INFO_SQLSTATE_INDEX] != PDO_ERROR_INFO_NO_ERROR_CODE) {
                $this->errors[__METHOD__] =
                    TOPIC_ID_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
            } else {
                $this->curTopicId = $topicId;
            }
        } else {
            $this->errors[__METHOD__] = TOPIC_ID_ERR_MSG;
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
        return ['text' => '',
            'topic_id' => $this->curTopicId,
            'status' => QUESTION_NOT_ANSWERED,
            'is_published' => QUESTION_NOT_PUBLISHED
        ];
    }

    /**
     * @return bool
     */
    public function setInputData()
    {
        return (
            $this->getParamSimple('text') &&
            $this->getParamNumeric('topic_id') &&
            $this->getParamLogical('is_published') &&
            $this->getParamLogical('status')
        );
    }

    /**
     * @param array $intrusion
     */
    public function renderResultPage($intrusion = [])
    {
        $this->getQuestionsList($intrusion);
        
        parent::renderResultPage(
            ['topic_id' => $this->curTopicId,
            'block' => $this->outputBlock,
            'type' => 'insert',
            'filter' => $this->getFilter()
            ]
        );
    }

    /**
     * @param array $intrusion
     */
    private function getQuestionsList($intrusion = [])
    {
        $questionsList =[];
        if ($this->curTopicId != UNKNOWN_ITEM_ID) {
            switch ($this->getFilter()) {
                case "all":
                    $questionsList = $this->model->getList($this->curTopicId);
                    break;
                case "unanswered":
                    $questionsList = $this->model->getUnansweredList($this->curTopicId);
                    break;
                case "unpublished":
                    $questionsList = $this->model->getUnpublishedList($this->curTopicId);
                    break;
                case "published":
                default:
                    $questionsList = $this->model->getPublishedList($this->curTopicId);
                    break;
            }
            $this->outputBlock = $this->render(
                $this->modelName . '/list.php',
                ['topic_id' => $this->curTopicId,
                'questions' => $questionsList,
                'intrusion' => $intrusion,
                'filter' => $this->getFilter()]
            );
        } else {
            $this->errors[__METHOD__] = TOPIC_ID_OF_QUESTION_ERR_MSG;
            $this->messages[__METHOD__] = QUESTIONS_LIST_FAILURE_MSG;
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
                'topics' => $this->getDataset(),
                'filter' => $this->getFilter()]
            );
        } else {
            $block = $this->render(
                $this->modelName . '/add.php',
                [$this->itemName => $this->getEmptyItem(),
                'filter' => $this->getFilter()]
            );
        }
        
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
            'block' => $block,
            'type' => $this->getItemIntrusionType,
            'hideEditQuestionButton' => true]
        );
    }

    /**
     *
     */
    public function addItem()
    {
        if (!(isset($_SESSION['user']['email']))) {
            if (isset($_GET['login'])
                    && preg_match(LOGIN_REGEXP, $_GET['login'])
                    && isset($_GET['email'])
                    && preg_match(EMAIL_REGEXP, $_GET['email'])) {
                $data['login'] = $_GET['login'];
                $data['email'] = $_GET['email'];
                $data['type'] = USER_CODE;
                $data['password'] ='';
                $user = $this->users->getUserByLogin($data['login']);
                if ($this->users->isUserUnique($data['login'], UNKNOWN_ITEM_ID)
                        && ($user['email'] != $data['email'])) {
                    if ($this->users->add($data)) {
                        $_SESSION['user'] = $this->users->getUserByLogin($data['login']);
                        $this->application->addActionButton('logout');
                    } else {
                        $errorInfo = $this->users->getLastPDOError();
                        $this->errors[__METHOD__] =
                            ITEM_ADD_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
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
                $this->messages[__METHOD__] = ITEM_UPDATE_SUCCESS_MSG;
            } else {
                $errorInfo = $this->model->getLastPDOError();
                $this->errors[__METHOD__] =
                    ITEM_UPDATE_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
                $this->messages[__METHOD__] = ITEM_UPDATE_FAILURE_MSG;
                $block = $this->render(
                    $this->modelName . '/update.php',
                    [$this->itemName => $this->getCurrentItem(),
                    'topics' => $this->getDataset()]
                );
            }
        } else {
            $block = $this->render(
                $this->modelName . '/update.php',
                [$this->itemName => $this->getCurrentItem(),
                'topics' => $this->getDataset()]
            );
            $this->messages[__METHOD__] = ITEM_UPDATE_FAILURE_MSG;
        }
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
            'block' => $block,
            'type' => $this->updateItemIntrusionType]
        );
    }
}
