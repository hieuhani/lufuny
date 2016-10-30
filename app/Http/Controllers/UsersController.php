<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function me(Request $request)
    {
        if ($request['user'] instanceof User) {
            return 'Hello ' . $request['user']->name;
        }
        return 'me';
    }
}
