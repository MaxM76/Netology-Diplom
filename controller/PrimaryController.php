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
    protected $currentItemID = -1;

    /**
     * @var string
     */
    protected $intrusionPlaceName = ''; //'topic_id', 'question_id'

    /**
     * @var int
     */
    protected $intrusionPlaceValue = -1;

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
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render($template, $params = [])
    {
        $result = '';
        $fileTemplate = 'template/'. $template;
        if (is_file($fileTemplate)) {
            $params = $params + ['controller' => $this->application->router->controllerName];
            $params = $params + ['action' => $this->application->router->action];
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
    public function renderErrorsBlock()
    {
        $this->errorsBlock = $this->render('errors.php', ['errors' => $this->errors]);
    }

    /**
     *
     */
    public function renderMessagesBlock()
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
    public function getParamSimple($name)
    {
        $success = false;
        if (isset($_GET[$name])) {
            $this->data[$name] = $_GET[$name];
            $success = true;
        } else {
            $this->errors[$name] = 'Error in getting ' . $name;
        }
        return $success;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getParamNumeric($name)
    {
        $success = false;
        if (isset($_GET[$name]) && is_numeric($_GET[$name])) {
            $this->data[$name] = $_GET[$name];
            $success = true;
        } else {
            $this->errors[$name] = 'Error in getting ' . $name;
        }
        return $success;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getParamLogical($name)
    {
        $this->data[$name] = false;
        $success = true;
        $this->messages[$name] = 'Using default value for ' . $name;
        return $success;
    }

    /**
     * @param array $intrusion
     */
    public function renderOutputBlock($intrusion = [])
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
    public function getDataset()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function checkUser()
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
        $this->renderOutputBlock($intrusion); // setting $this->outputBlock
        $this->renderErrorsBlock(); // setting $this->errorsBlock
        $this->renderMessagesBlock(); // setting $this->messagesBlock
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
    public function setCurrentItemId()
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
    public function setIntrusionPlaceValue($value = -1)
    {
        $this->intrusionPlaceValue = $value;
    }

    /**
     * @return bool
     */
    public function isCurrentItemExist()
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
                $this->messages['deleteItem'] = 'Item deleted';
                $this->currentItemID = -1;
            } else {
                $errorInfo = $this->model->getLastPDOError()['2'];
                $this->errors['deleteItem'] = 'Item not deleted. Database Error: ' . $errorInfo['2'];
                $this->messages['deleteItem'] = 'Item not deleted';
            }
        } else {
            $this->messages['deleteItem'] = 'Item not deleted';
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
    public function getEmptyItem()
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
    public function setInputData()
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
                $this->messages['addItem'] = 'Item added!';
            } else {
                $errorInfo = $this->model->getLastPDOError()['2'];
                $this->errors['addItem'] = 'Item not added. Database Error: ' . $errorInfo['2'];
                $this->messages['addItem'] = 'Item not added';
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
            $this->messages['addItem'] = 'Item not added';
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
                $this->messages['updateItem'] = 'Item updated!';
            } else {
                $errorInfo = $this->model->getLastPDOError()['2'];
                $this->errors['updateItem'] = 'Item is not updated. Database Error: ' . $errorInfo['2'];
                $this->messages['updateItem'] = 'Item not updated!';
                $block = $this->render(
                    $this->modelName . '/update.php',
                    [$this->itemName => $this->currentItem]
                );
            }
        } else {
            $block = $this->render(
                $this->modelName . '/update.php',
                [$this->itemName => $this->currentItem]
            );
            $this->messages['updateItem'] = 'Item not updated!';
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
