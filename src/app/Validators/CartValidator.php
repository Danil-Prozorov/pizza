<?php

namespace App\Validators;

use App\Models\Product;
use Exception;
class CartValidator
{

    public function validate($options):bool
    {
        if(is_numeric($options) || is_string($options)) {
            return $this->checkAvailabilityStatus($options);
        }

        if(is_array($options) || is_object($options)) {
            return $this->checkAvailabilityStatusForFew($options);
        }

        throw new Exception('Validation error: Cannot validate this data type');
    }

    protected function checkAvailabilityStatus($product_id):bool
    {
        $product_status = Product::find($product_id)->product_status;

        if($product_status->status_name != 'active'){
            return false;
        }

        return true;
    }

    protected function checkAvailabilityStatusForFew($products):bool
    {

        foreach($products as $product){
            if(!$this->checkAvailabilityStatus($product->product_id)){
                return $this->checkAvailabilityStatus($product->product_id);
            }
        }

        return true;
    }

}
