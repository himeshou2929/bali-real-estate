<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inquiry;

class InquiryPolicy
{
    public function view(User $user, Inquiry $inquiry): bool
    {
        return $user->isAdmin()
            || ($user->isAgent() && $inquiry->agent_id === $user->id)
            || $user->isViewer();
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return $user->isAdmin()
            || ($user->isAgent() && $inquiry->agent_id === $user->id);
    }

    public function assign(User $user): bool
    {
        return $user->isAdmin();
    }

    public function reply(User $user, Inquiry $inquiry): bool
    {
        return $user->isAdmin()
            || ($user->isAgent() && $inquiry->agent_id === $user->id);
    }
}