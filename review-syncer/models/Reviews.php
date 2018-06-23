<?php

namespace app\models;

use Yii;
use Yii\db\ActiveRecord;


class Reviews extends ActiveRecord
{

    protected $slug    = null;
    protected $product = null;

    protected $url  = 'https://apps.shopify.com/{slug}/reviews.json';

    public static function tableName()
    {
        return 'shopify_app_reviews';
    }

    protected function resolveUrl()
    {
        assert('$this->slug !== null');

        return str_replace('{slug}', $this->slug, $this->url);
    }

    public function fetchData($slug = null, $product = null)
    {
        assert('$slug !== null && $product !== null');

        $request  = \curl_init();
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_URL, $this->resolveUrl());
        $result = curl_exec($request);
        $return = json_decode($result, true);
        curl_close($request);

        $reviews = $return['reviews'];

        foreach ($reviews as $key => $data)
        {

            $dateCreated = new \DateTime($data['created_at' ]);
            $shopName    = (string)$data['shop_name'];
            $domain      = (string)$data['shop_domain'];
            $starRating  = (int)   $data['star_rating'];

            $searchData = array(
                'shop_name' => $shopName,
                'app_slug'  => $slug
            );

            $review = Reviews::find()->where($searchData)->one();

            if ($review !== null)
            {
                $dateLast     = new \DateTime($review->updated_at);
                $starLast     = (int)$review->star_rating;

                if ($dateCreated > $dateLast && $starLast !== $starRating)
                {
                    $review->updated_at           = new \DateTime($data['created_at' ]);
                    $review->star_rating          = $starRating;
                    $review->previous_star_rating = $starLast;
                    $review->save();
                }
            }
            else
            {
                $dateCreated = new \DateTime($data['created_at' ]);
                $dateUpdated = new \DateTime($data['created_at' ]);

                $review = new Reviews();
                $review->app_slug             = $slug;
                $review->shopify_domain       = $domain;
                $review->shop_name            = $shopName;;
                $review->star_rating          = $starRating;
                $review->previous_star_rating = null;
                $review->created_at           = $dateCreated->format('Y-m-d h:i:s');
                $review->updated_at           = $dateUpdated->format('Y-m-d h:i:s');
                $review->save();
            }

        }

        return [count($reviews), $product];

    }

}
