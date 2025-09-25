<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Property;

class PropertyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'agent';
    }

    public function view(User $user, Property $property): bool
    {
        return true; // 全ユーザー閲覧可
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'agent';
    }

    public function update(User $user, Property $property): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'agent' && $property->agent_id === $user->id);
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'agent' && $property->agent_id === $user->id);
    }
}