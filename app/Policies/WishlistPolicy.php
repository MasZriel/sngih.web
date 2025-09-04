<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id;
    }
}