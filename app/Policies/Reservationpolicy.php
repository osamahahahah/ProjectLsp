<?php

namespace App\Policies;

use App\Models\User;
use App\Models\reservation;
use Illuminate\Auth\Access\Response;

class Reservationpolicy
{
    // /**
    //  * Determine whether the user can view any models.
    //  */
    // public function viewAny(User $user): bool
    // {


    //     if($user->can('Reservations'))
    //     return true;
    //    else
    //     return false;

    // }

    // /**
    //  * Determine whether the user can view the model.
    //  */
    // public function view(User $user, reservation $reservation): bool
    // {
    //     if($user->can('Reservations'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can create models.
    //  */
    // public function create(User $user): bool
    // {
    //     if($user->can('Reservations'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can update the model.
    //  */
    // public function update(User $user, reservation $reservation): bool
    // {
    //     if($user->can('Reservations'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can delete the model.
    //  */
    // public function delete(User $user, reservation $reservation): bool
    // {
    //     if($user->can('Reservations'))
    //     return true;
    //    else
    //     return false;
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, reservation $reservation): bool
    // {
    //     return false;
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, reservation $reservation): bool
    // {
    //     return false;
    // }
}
