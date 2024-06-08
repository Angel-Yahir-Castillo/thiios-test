<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();
        return jsonResponse(data: new UserCollection($users));
    }

    public function store(CreateUserRequest $request)
    {
        //create a user with the validated data
        $user = User::create($request->validated());

        //return the user data
        return jsonResponse(data: ['user' => UserResource::make($user)]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return jsonResponse(message: 'User not found', status: 404);
        }

        $user->update($request->validated());

        return jsonResponse(
            data: ['user' => UserResource::make($user->fresh())],
            message: 'User updated successfully'
        );

    }
    public function destroy($id)
    {
        //find the user with the id passed
        $user = User::find($id);

        //if not find the user, return 404 status
        if (!$user) {
            return jsonResponse(message: 'User not found', status: 404);
        }

        //delete the user
        $user->delete();

        //reponse with the correct message
        return jsonResponse(message: 'User deleted successfully');
    }
}
