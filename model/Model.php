<?php

namespace lh\model;

use lh\controller\PrimaryController;

/**
 * Class Model
 * @package lh\model
 */
class Model
{
    /**
     * @var PrimaryController
     */
    private $controller;
    /**
     * @var array
     */
    protected $lastPDOError = [];

    /**
     * Model constructor.
     * @param PrimaryController $controller
     */
    public function __construct(PrimaryController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return \PDO
     */
    public function getDatabase()
    {
        return $this->controller->getApplication()->getDatabase();
    }

    /**
     * @return array
     */
    public function getDatabaseError()
    {
        return $this->getDatabase()->errorInfo();
    }

    /**
     * @return array
     */
    public function getLastPDOError()
    {
        return $this->lastPDOError;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function add($params)
    {
        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return false;
    }

    /**
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update($id, $params)
    {
        return false;
    }

    /**
     * @param int $ownerId
     * @return bool
     */
    public function getList($ownerId = UNKNOWN_ITEM_ID)
    {
        return false;
    }

    /**
     * @param int $id
     * @return null
     */
    public function getItem($id)
    {
        return null;
    }
}