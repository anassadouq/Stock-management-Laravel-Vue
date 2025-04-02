<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }   

    public function index()
    {
        $users = $this->userService->getUsers();
        return response()->json($users);
    }

    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $updateUser = $this->userService->updateUser($user, $request->all());
        return response()->json($updateUser);
    }    

    public function destroy(User $user)
    {
        return $this->userService->deleteUser($user);
    }
}