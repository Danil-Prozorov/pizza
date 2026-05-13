<?php

namespace App\Contracts;

interface AdminUserUpdateContract
{
    public function update($id, $userdata);
}
