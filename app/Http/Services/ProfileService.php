<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProfileService extends BaseService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
    }

    /**
     * Update profile data for a user model instance.
     */
    public function updateProfile(Model $user, array $data)
    {
        // if password provided, hash it
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data);
        $user->save();

        return $user;
    }

    /**
     * Delete a user.
     */
    public function deleteUser(Model $user): void
    {
        $this->userRepository->destroy((string)$user->id);
    }
}
