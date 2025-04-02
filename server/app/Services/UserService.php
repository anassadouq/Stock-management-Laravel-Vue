<?php

namespace App\Services;

use App\Interfaces\UserInterface;

class UserService
{
    public $UserInterface;

    public function __construct(UserInterface $UserInterface)
    {
        $this->UserInterface = $UserInterface;
    }

    // Fetch users
    public function getUsers()
    {
        return $this->UserInterface->getUsers();
    }

    // Save user
    public function saveUser($data)
    {
        return $this->UserInterface->saveUser($data);
    }

    // Update user
    public function updateUser($user, $data)
    {
        return $this->UserInterface->updateUser($user, $data);
    }

    // Delete user
    public function deleteUser($user)
    {
        return $this->UserInterface->deleteUser($user);
    }
}