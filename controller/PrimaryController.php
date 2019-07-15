<?php

class PrimaryController
{
    protected $model = null; // model
    protected $users = null;
    protected $errors = [];
    protected $messages =[];
    private $router;
    protected $modelName = ''; // topics, questions, answers, users
    protected $data = [];

    protected $outputTemplate ='';
    protected $errorsBlock = '';
    protected $messagesBlock = '';
    protected $outputBlock = '';
    protected $datasetName = '';
    protected $itemName = ''; // topic, question, answer
    protected $currentItemID = -1;
    protected $intrusionPlaceName = ''; //'topic_id', 'question_id'
    protected $intrusionPlaceValue = -1;
    protected $currentItem = [];

    protected $addItemIntrusionType;
    protected $updateItemIntrusionType;
    protected $getItemIntrusionType;



    function __construct($router)
    {
        $this -> router = $router;
        $this -> users = new Users($this);
        $this -> initModels();
        $this -> setCurrentItemId();

        $this -> addItemIntrusionType = 'replace';
        $this -> updateItemIntrusionType = 'replace';
        $this -> getItemIntrusionType = 'replace';

    }

    protected function initModels()
    {

    }

    public function getRouter()
    {
        return $this -> router;
    }

    public function render($template, $params = [])
    {
        $result = '';
        $fileTemplate = 'template/'. $template;
        if (is_file($fileTemplate)) {
            ob_start();
            if (count($params) > 0) {
                extract($params);
            }
            include $fileTemplate;               
            $result = ob_get_clean();
        }
        return $result;
    }

    public function renderErrorsBlock()
    {
        $this -> errorsBlock = $this -> render('errors.php', ['errors' => $this -> errors]);
    }

    public function renderMessagesBlock()
    {
        $this -> messagesBlock = $this -> render('messages.php', ['messages' => $this -> messages]);
    }

    public function getMessagesBlock()
    {
        return $this -> messagesBlock;
    }

    public function getErrorsBlock()
    {
        return $this -> errorsBlock;
    }

    public function getOutputBlock()
    {
        return $this -> outputBlock;
    }

    public function getParamSimple($name)
    {
        $success = false;        
        if (isset($_GET[$name])) {
            $this -> data[$name] = $_GET[$name];
            $success = true;
        } else {
            $this -> errors[$name] = 'Error in getting ' . $name;
        }
        return $success;
    }


    public function getParamNumeric($name)
    {
        $success = false;        
        if (isset($_GET[$name]) && is_numeric($_GET[$name])) {
            $this -> data[$name] = $_GET[$name];
            $success = true;
        } else {
            $this -> errors[$name] = 'Error in getting ' . $name;
        }
        return $success;
    }


    public function getParamLogical($name)
    {
        $this -> data[$name] = false;
        $success = true;
        $this -> messages[$name] = 'Using default value for ' . $name;
        return $success;
    }


    public function renderOutputBlock($intrusion = [])
    {
        $this -> outputBlock = $this -> render(
            $this -> outputTemplate,
            [$this -> datasetName => $this -> getDataset(),
                'intrusion' => $intrusion]
        );
    }

    public function getDataset()
    {

    }


    public function checkUser() 
    {
        $success = false;
        if (isset($_SESSION['user']['mail'])) {
            if (isset($_SESSION['user']['user_id'])) {
                $this -> data['author'] = $_SESSION['user']['user_id'];
            }
        $success = true;
        }
        return $success;
    }


    public function renderResultPage($intrusion = [])
    {
        $this -> renderOutputBlock($intrusion); // setting $this -> outputBlock
        $this -> renderErrorsBlock(); // setting $this -> errorsBlock
        $this -> renderMessagesBlock(); // setting $this -> messagesBlock
        echo $this -> render(
            'html.php', [
                'buttons' => $this -> router -> actionButtons,
                'mblock' => $this -> messagesBlock,
                'eblock' => $this -> errorsBlock,
                'oblock' => $this -> outputBlock]
            );
    }


    public function setCurrentItemId()
    {
        $result = false;
        if ((count($_GET) > 0) && isset($_GET['id']) && is_numeric($_GET['id'])) {
            $this -> currentItemID = $_GET['id'];
            $this -> setIntrusionPlaceValue($this -> currentItemID);
            $result = true;
        }
        return $result;
    }

    public function setIntrusionPlaceValue($value = -1)
    {
        $this -> intrusionPlaceValue = $value;
    }
    

    public function isCurrentItemExist()
    {
        $result = false;
        if ($this -> currentItemID > 0) {
            $result = true;
        }
        return $result;
    }


    public function deleteItem()
    {
        if ($this -> isCurrentItemExist()) {
            $isDelete = $this -> model -> delete($this -> currentItemID);
            if ($isDelete) {
                $this -> itemDeleted();
                $this -> messages['deleteItem'] = 'Item deleted';
                $this -> currentItemID = -1;             
            } else {
                $errorInfo = $this -> model -> getLastPDOError()['2'];
                $this -> errors['deleteItem'] = 'Item not deleted. Database Error: ' . $errorInfo;
                $this -> messages['deleteItem'] = 'Item not deleted';
            }
        } else {
            $this -> messages['deleteItem'] = 'Item not deleted';
        }
        $this -> renderResultPage();
    }


    public function getCurrentItem()
    {
        return;
    }

    public function getEmptyItem()
    {
        return;
    }


    public function getItem()
    {
        $block = '';
        if ($this -> isCurrentItemExist()) {
            $block = $this -> render(
                $this -> modelName . '/update.php',
                [$this -> itemName => $this -> getCurrentItem()]
            );
        } else {
            $block = $this -> render(
                $this -> modelName . '/add.php',
                [$this -> itemName => $this -> getEmptyItem()]
            );
        }            
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => $this -> intrusionPlaceValue,
            'block' => $block,
            'type' => $this -> getItemIntrusionType]
        );
    }


    public function setInputData()
    {
        $success = false;
        return $success;
    }


	public function addItem()
	{
        $block = '';

        if ($this -> setInputData() && $this -> checkUser()) {
            $isAdded = $this -> model -> add($this -> data);
            if ($isAdded) {
                $this -> itemAdded();
                $this -> messages['addItem'] = 'Item added!';
            } else {
                $errorInfo = $this -> model -> getLastPDOError()['2'];
                $this -> errors['addItem'] = 'Item not added. Database Error: ' . $errorInfo;
                $this -> messages['addItem'] = 'Item not added';
                $block = $this -> render(
                    $this -> modelName . '/add.php',
                    [$this -> itemName => $this -> getEmptyItem()]
                );
            }
        } else {
            $block = $this -> render(
                $this -> modelName . '/add.php',
                [$this -> itemName => $this -> getEmptyItem()]
            );
            $this -> messages['addItem'] = 'Item not added';
        }

        $this -> renderResultPage(
            [$this -> intrusionPlaceName => $this -> intrusionPlaceValue,
            'block' => $block,
            'type' => $this -> addItemIntrusionType]
        );
	}



    public function updateItem()
    {
        $block = '';
        if ($this-> isCurrentItemExist() && $this -> setInputData()) {
            $isUpdate = $this -> model -> update($this -> currentItemID, $this -> data);
            if ($isUpdate) {
                $this -> itemUpdated();
                $this -> messages['updateItem'] = 'Item updated!';
            } else {
                $errorInfo = $this -> model -> getLastPDOError()['2'];
                $this -> errors['updateItem'] = 'Item is not updated. Database Error: ' . $errorInfo;
                $this -> messages['updateItem'] = 'Item not updated!';
                $block = $this -> render(
                    $this -> modelName . '/update.php',
                    [$this -> itemName => $this -> currentItem]
                );
            }
        } else {
            $block = $this -> render(
                $this -> modelName . '/update.php',
                [$this -> itemName => $this -> currentItem]
            );
            $this -> messages['updateItem'] = 'Item not updated!';
        }
        $this -> renderResultPage(
            [$this -> intrusionPlaceName => $this -> intrusionPlaceValue, 
            'block' => $block,
            'type' => $this -> updateItemIntrusionType]
        );
    }

    public function itemDeleted()
    {
        
    }

    public function itemAdded()
    {

    }

    public function itemUpdated()
    {

    }
}