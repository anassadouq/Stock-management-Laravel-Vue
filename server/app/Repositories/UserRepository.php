<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    // Fetch users
    public function getUsers()
    {
        return User::all();
    }

    // Save user
    public function saveUser($user)
    {
        return User::create($user);
    }

    // Update user
    public function updateUser($user, $data)
    {
        if ($user->update($data)) {
            return $user->fresh();
        }
        return null; // Let the controller handle errors
    }
    
    // Delete user
    public function deleteUser($user)
    {
        if ($user->delete()) {
            return response()->json(['message' => 'user deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete user'], 500);
    }
}