<?php

namespace App\Policies;

use App\Models\User;
use App\Models\room;
use Illuminate\Auth\Access\Response;

class Roompolicy
{
    /**
     * Determine whether the user can view any models.
      */
    // public function viewAny(User $user): bool
    // {
    //     if($user->can('Room'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can view the model.
    //  */
    // public function view(User $user, room $room): bool
    // {
    //     if($user->can('Rooms'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can create models.
    //  */
    // public function create(User $user): bool
    // {
    //     if($user->can('Rooms'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can update the model.
    //  */
    // public function update(User $user, room $room): bool
    // {
    //     if($user->can('Rooms'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can delete the model.
    //  */
    // public function delete(User $user, room $room): bool
    // {
    //     if($user->can('Rooms'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, room $room): bool
    // {
    //     return false;
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, room $room): bool
    // {
    //     return false;
    // }
}
