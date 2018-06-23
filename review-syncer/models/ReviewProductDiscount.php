<?php

namespace app\models;

use Yii;
use Yii\db\ActiveRecord;


class ReviewProductDiscount extends Reviews
{

    protected $slug    = 'product-discount';
    protected $product = 'Product Discount';

    public function fetchData($slug = null, $product = null)
    {
        return parent::fetchData($this->slug, $this->product);
    }

}
