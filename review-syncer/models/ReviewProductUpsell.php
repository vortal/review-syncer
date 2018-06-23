<?php

namespace app\models;

use Yii;
use Yii\db\ActiveRecord;


class ReviewProductUpsell extends Reviews
{

    protected $slug    = 'product-upsell';
    protected $product = 'Product Upsell';

    public function fetchData($slug = null, $product = null)
    {
        return parent::fetchData($this->slug, $this->product);
    }

}
