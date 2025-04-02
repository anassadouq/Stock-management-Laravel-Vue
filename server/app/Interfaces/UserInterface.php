<?php

namespace App\Interfaces;

interface UserInterface
{
    // Fetch all users
    public function getUsers();

    // Save user
    public function saveUser($data);

    // Update user
    public function updateUser($user, $data);

    // Update user
    public function deleteUser($user);
}