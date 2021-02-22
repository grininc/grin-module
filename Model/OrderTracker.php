<?php

declare(strict_types=1);

namespace Grin\Affiliate\Model;

class OrderTracker
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
