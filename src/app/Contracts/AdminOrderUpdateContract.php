<?php

namespace App\Contracts;

interface AdminOrderUpdateContract
{
    public function update($id, $params);
}
