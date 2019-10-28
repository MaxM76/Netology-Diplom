<?php

namespace lh\controller;

use lh\model\Topics;
use lh\classes\Application;

/**
 * Class TopicsController
 * @package lh\controller
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
        // $this->addItemIntrusionType = 'replace';
        $this->updateItemIntrusionType = 'insert';
        $this->getItemIntrusionType = 'replace';
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
    protected function getDataset()
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
    protected function getEmptyItem()
    {
        return ['text' => '', 'description' => ''];
    }

    /**
     * @return bool
     */
    protected function setInputData()
    {
        return ($this->getParamSimple('text') && $this->getParamSimple('description'));
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
                'type' => $this->getItemIntrusionType,
                'hideUpdateTopicButton' => true,
                'filter' => $this->getFilter()]
        );
    }
}
