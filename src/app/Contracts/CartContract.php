<?php

namespace App\Contracts;

interface CartContract
{
    public function index();

    public function store($item_data);

    public function destroy($request_data);
}
