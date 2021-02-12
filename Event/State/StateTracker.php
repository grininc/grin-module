<?php

namespace Grin\GrinModule\Event\State;

/**
 * Class StateTracker
 * Add Hoc solution to track model state
 *
 * @package Grin\GrinModule\Event\State
 */
abstract class StateTracker
{
    /**
     * @var boolean
     */
    protected $new = false;

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->new = $model->isObjectNew();
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->new;
    }
}