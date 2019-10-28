<?php

namespace lh\controller;

use lh\classes\Application;
use lh\model\Model;
use lh\model\Users;

class PrimaryController
{
    /**
     * @var Model|null
     */
    protected $model = null;

    /**
     * @var Users|null
     */
    protected $users = null;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $messages =[];

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var string
     */
    protected $modelName = ''; // topics, questions, answers, users

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $outputTemplate ='';

    /**
     * @var string
     */
    protected $errorsBlock = '';

    /**
     * @var string
     */
    protected $messagesBlock = '';

    /**
     * @var string
     */
    protected $outputBlock = '';

    /**
     * @var string
     */
    protected $datasetName = '';

    /**
     * @var string
     */
    protected $itemName = ''; // topic, question, answer

    /**
     * @var int
     */
    protected $currentItemID = UNKNOWN_ITEM_ID;

    /**
     * @var string
     */
    protected $intrusionPlaceName = ''; //'topic_id', 'question_id'

    /**
     * @var int
     */
    protected $intrusionPlaceValue = UNKNOWN_ITEM_ID;

    /**
     * @var array
     */
    protected $currentItem = [];

    /**
     * @var string
     */
    protected $addItemIntrusionType;

    /**
     * @var string
     */
    protected $updateItemIntrusionType;

    /**
     * @var string
     */
    protected $getItemIntrusionType;

    /**
     * PrimaryController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->users = new Users($this);
        $this->initModels();
        $this->setCurrentItemId();
        $this->setFilter();
        $this->addItemIntrusionType = 'replace';
        $this->updateItemIntrusionType = 'replace';
        $this->getItemIntrusionType = 'replace';
    }

    /**
     *
     */
    protected function initModels()
    {
        $this->model = new Model($this);
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     *
     */
    public function getFilter()
    {
        return (isset($_SESSION['filter'])) ? $_SESSION['filter'] : PUBLISHED_QUESTIONS;
    }

    /**
     *
     */
    protected function setFilter()
    {
        if (isset($_GET['filter']) && isset($_SESSION['user']['type']) && $_SESSION['user']['type'] != QUEST_CODE) {
            $_SESSION['filter'] = $_GET['filter'];
        }
    }

    /**
     * @param string $template
     * @param array $params
     * @return string
     */
    protected function render($template, $params = [])
    {
        $result = '';
        $fileTemplate = 'template/'. $template;
        if (is_file($fileTemplate)) {
            $params = $params + ['userType' => $this->application->userType];
            ob_start();
            if (count($params) > 0) {
                extract($params);
            }
            include $fileTemplate;
            $result = ob_get_clean();
        }
        return $result;
    }

    /**
     *
     */
    protected function renderErrorsBlock()
    {
        $this->errorsBlock = $this->render('errors.php', ['errors' => $this->errors]);
    }

    /**
     *
     */
    protected function renderMessagesBlock()
    {
        $this->messagesBlock = $this->render('messages.php', ['messages' => $this->messages]);
    }

    /**
     * @return string
     */
    public function getMessagesBlock()
    {
        return $this->messagesBlock;
    }

    /**
     * @return string
     */
    public function getErrorsBlock()
    {
        return $this->errorsBlock;
    }

    /**
     * @return string
     */
    public function getOutputBlock()
    {
        return $this->outputBlock;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function getParamSimple($name)
    {
        $success = false;
        if (isset($_GET[$name])) {
            $this->data[$name] = $_GET[$name];
            $success = true;
        } else {
            $this->errors[__METHOD__] = GETTING_VALUE_ERR_MSG . $name;
        }
        return $success;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function getParamNumeric($name)
    {
        $success = false;
        if (isset($_GET[$name]) && is_numeric($_GET[$name])) {
            $this->data[$name] = $_GET[$name];
            $success = true;
        } else {
            $this->errors[__METHOD__] = GETTING_VALUE_ERR_MSG . $name;
        }
        return $success;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function getParamLogical($name)
    {
        $this->data[$name] = false;
        if (isset($_GET[$name])) {
            $input = $_GET[$name];
            if (is_bool($input)) {
                $this->data[$name] = $input;
                return true;
            } else {
                if (($input == 1) || ($input == 'on')) {
                    $this->data[$name] = true;
                    return true;
                } elseif (($input == 0)) {
                    return true;
                }
            }
        } else {
            $this->messages[__METHOD__] = ITEM_DEFAULT_VALUE_MSG . $name;
            return true;
        }
    }

    /**
     * @param array $intrusion
     */
    private function renderOutputBlock($intrusion = [])
    {
        $this->outputBlock = $this->render(
            $this->outputTemplate,
            [$this->datasetName => $this->getDataset(),
                'intrusion' => $intrusion]
        );
    }

    /**
     * @return null
     */
    protected function getDataset()
    {
        return null;
    }

    /**
     * @return bool
     */
    protected function checkUser()
    {
        $success = false;
        if (isset($_SESSION['user']['email'])) {
            if (isset($_SESSION['user']['id'])) {
                $this->data['author'] = $_SESSION['user']['id'];
            }
            $success = true;
        }
        return $success;
    }

    /**
     * @param array $intrusion
     */
    public function renderResultPage($intrusion = [])
    {
        $this->renderOutputBlock($intrusion);
        $this->renderErrorsBlock();
        $this->renderMessagesBlock();
        echo $this->render(
            'html.php',
            [
                'buttons' => $this->application->actionButtons,
                'mblock' => $this->messagesBlock,
                'eblock' => $this->errorsBlock,
                'oblock' => $this->outputBlock
            ]
        );
    }

    /**
     * @return bool
     */
    protected function setCurrentItemId()
    {
        $result = false;
        if ((count($_GET) > 0) && isset($_GET['id']) && is_numeric($_GET['id'])) {
            $this->currentItemID = $_GET['id'];
            $this->setIntrusionPlaceValue($this->currentItemID);
            $result = true;
        }
        return $result;
    }

    /**
     * @param int $value
     */
    protected function setIntrusionPlaceValue($value = UNKNOWN_ITEM_ID)
    {
        $this->intrusionPlaceValue = $value;
    }

    /**
     * @return bool
     */
    protected function isCurrentItemExist()
    {
        $result = false;
        if ($this->currentItemID > 0) {
            $result = true;
        }
        return $result;
    }

    /**
     *
     */
    public function deleteItem()
    {
        if ($this->isCurrentItemExist()) {
            $isDelete = $this->model->delete($this->currentItemID);
            if ($isDelete) {
                $this->itemDeleted();
                $this->messages[__METHOD__] = ITEM_DELETE_SUCCESS_MSG;
                $this->currentItemID = UNKNOWN_ITEM_ID;
            } else {
                $errorInfo = $this->model->getLastPDOError();
                $this->errors[__METHOD__] =
                    ITEM_DELETE_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
                $this->messages[__METHOD__] = ITEM_DELETE_FAILURE_MSG;
            }
        } else {
            $this->messages[__METHOD__] = ITEM_DELETE_FAILURE_MSG;
        }
        $this->renderResultPage();
    }

    /**
     * @return null
     */
    public function getCurrentItem()
    {
        return null;
    }

    /**
     * @return null
     */
    protected function getEmptyItem()
    {
        return null;
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
     * @return bool
     */
    protected function setInputData()
    {
        $success = false;
        return $success;
    }

    /**
     *
     */
    public function addItem()
    {
        $block = '';
        if ($this->setInputData() && $this->checkUser()) {
            $isAdded = $this->model->add($this->data);
            if ($isAdded) {
                $this->itemAdded();
                $this->messages[__METHOD__] = ITEM_ADD_SUCCESS_MSG;
            } else {
                $errorInfo = $this->model->getLastPDOError();
                $this->errors[__METHOD__] =
                    ITEM_ADD_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
                $this->messages[__METHOD__] = ITEM_ADD_FAILURE_MSG;
                $block = $this->render(
                    $this->modelName . '/add.php',
                    [$this->itemName => $this->getEmptyItem()]
                );
            }
        } else {
            $block = $this->render(
                $this->modelName . '/add.php',
                [$this->itemName => $this->getEmptyItem()]
            );
            $this->messages[__METHOD__] = ITEM_ADD_FAILURE_MSG;
        }
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
            'block' => $block,
            'type' => $this->addItemIntrusionType]
        );
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
                $this->setIntrusionPlaceValue();
            } else {
                $errorInfo = $this->model->getLastPDOError();
                $this->errors[__METHOD__] =
                    ITEM_UPDATE_DB_ERR_MSG . $errorInfo[PDO_ERROR_INFO_MSG_INDEX];
                $this->messages[__METHOD__] = ITEM_UPDATE_FAILURE_MSG;
                $block = $this->render(
                    $this->modelName . '/update.php',
                    [$this->itemName => $this->getCurrentItem()]
                );
            }
        } else {
            $block = $this->render(
                $this->modelName . '/update.php',
                [$this->itemName => $this->getCurrentItem()]
            );
            $this->messages[__METHOD__] = ITEM_UPDATE_FAILURE_MSG;
        }
        $this->renderResultPage(
            [$this->intrusionPlaceName => $this->intrusionPlaceValue,
            'block' => $block,
            'type' => $this->updateItemIntrusionType]
        );
    }

    /**
     *
     */
    public function itemDeleted()
    {
    }

    /**
     *
     */
    public function itemAdded()
    {
    }

    /**
     *
     */
    public function itemUpdated()
    {
    }
}
