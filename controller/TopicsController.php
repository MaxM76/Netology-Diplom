<?php

namespace localhost\controller;

use localhost\model\Topics;
use localhost\classes\Application;

/**
 * Class TopicsController
 * @package localhost\controller
 */
class TopicsController extends PrimaryController
{
    /**
     * TopicsController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->intrusionPlaceName = 'topic_id';
        $this->outputTemplate ='topics/list.php';
    }

    /**
     *
     */
    protected function initModels()
    {
        $this->model = new Topics($this);
        $this->modelName = 'topics';
        $this->datasetName = 'topics';
        $this->itemName = 'topic'; // topic, question, answer
    }

    /**
     * @return bool
     */
    public function getDataset()
    {
        return $this->model->getList();
    }

    /**
     * @return array
     */
    public function getCurrentItem()
    {
        return $this->model->getItem($this->currentItemID);
    }

    /**
     * @return array
     */
    public function getEmptyItem()
    {
        return ['text' => '', 'description' => ''];
    }

    /**
     * @return bool
     */
    public function setInputData()
    {
        return ($this->getParamSimple('text') && $this->getParamSimple('description'));
    }
}
