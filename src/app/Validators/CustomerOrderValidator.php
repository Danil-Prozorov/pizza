<?php

namespace App\Validators;

use App\Models\Product;
use Exception;

class CustomerOrderValidator
{
    public function validateAccessibleAmount($id,$amount): bool
    {
        try{
            $product = Product::findOrFail(['id' => $id]);

            if($product[0]->stock < $amount && $amount > 0 && $product[0]->stock > 0){
                return false;
            }
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }

        return true;
    }
}
