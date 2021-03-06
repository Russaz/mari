<?php

namespace Featherwebs\Mari\Controllers;

use Featherwebs\Mari\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProfileController extends BaseController
{
    public function edit()
    {
        $user    = auth()->user();
        $roles   = Role::all();
        $profile = true;

        return view('featherwebs::admin.user.edit', compact('user', 'roles', 'profile'));
    }
}
