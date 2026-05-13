<?php

namespace App\Http\Controllers\AdminControllers;

use App\Contracts\AdminUserDestroyContract as AdminUserDestroy;
use App\Contracts\AdminUserCreateContract as AdminUserCreate;
use App\Contracts\AdminUserUpdateContract as AdminUserUpdate;
use App\Contracts\AdminUserIndexContract as AdminUserIndex;
use App\Contracts\AdminUserShowContract as AdminUserShow;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Http\Requests\Admin\AdminUserCreateRequest;
use App\Http\Controllers\Controller;


class AdminUsersController extends Controller
{
    public function index(AdminUserIndex $user)
    {
        return $user->index();
    }

    public function create(AdminUserCreateRequest $request,AdminUserCreate $user)
    {
        $userdata = $request->validated();

        return $user->create($userdata);
    }

    public function show($id,AdminUserShow $user)
    {
        return $user->show($id);
    }

    public function update($id, AdminUserUpdateRequest $request,AdminUserUpdate $user)
    {
        $userdata = $request->validated();

        return $user->update($id,$userdata);
    }

    public function destroy($id,AdminUserDestroy $user)
    {
        return $user->destroy($id);
    }
}
