<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();
        return jsonResponse(data: new UserCollection($users));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return jsonResponse(message: 'User not found', status: 404);
        }

        $user->delete();

        return jsonResponse(message: 'User deleted successfully');
    }
}
