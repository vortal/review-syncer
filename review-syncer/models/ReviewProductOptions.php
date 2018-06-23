<?php

namespace app\models;

use Yii;
use Yii\db\ActiveRecord;


class ReviewProductOptions extends Reviews
{

    protected $slug    = 'product-options';
    protected $product = 'Product Options';

    public function fetchData($slug = null, $product = null)
    {
        return parent::fetchData($this->slug, $this->product);
    }

}
