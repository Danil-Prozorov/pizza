<?php

namespace App\Contracts;

interface OrderContract
{
    public function index();
    public function show($id);
    public function create($params);
    public function destroy($id);
}
