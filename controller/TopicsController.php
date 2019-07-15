<?php

require_once 'PrimaryController.php';
include 'model/topics.php';


class TopicsController extends PrimaryController
{

    function __construct($router)
    {
        parent::__construct($router);

        $this -> intrusionPlaceName = 'topic_id';
        $this -> outputTemplate ='topics/list.php';
    }

    protected function initModels()
    {
        $this -> model = new Topics($this);
        $this -> modelName = 'topics';
        $this -> datasetName = 'topics';
        $this -> itemName = 'topic'; // topic, question, answer
    }

    public function getDataset()
    {
        return $this -> model -> getList();
    }


    public function getCurrentItem()
    {
        return $this -> model -> getItem($this -> currentItemID);
    }

    public function getEmptyItem()
    {
        return ['text' => '', 'description' => ''];
    }



    public function setInputData()
    {
        return ($this -> getParamSimple('text') && $this -> getParamSimple('description'));
    }       
   

}