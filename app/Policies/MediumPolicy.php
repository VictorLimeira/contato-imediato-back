<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\Medium;
use App\Models\User;

class MediumPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Contact $contact): bool
    {
        return $user->id === $contact->user_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Medium $medium): bool
    {
        return $user->id === $medium->contact->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Medium $medium): bool
    {
        return $user->id === $medium->contact->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Medium $medium): bool
    {
        return $user->id === $medium->contact->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contact $contact, Medium $medium): bool
    {
        return $user->id === $medium->contact->user_id &&
                $contact->id === $medium->contact_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Medium $medium): bool
    {
        return false;
    }
}
