<?php

namespace App\Policies;

use App\Models\Faqs;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        foreach ($user->roles as $role) {
            switch ($role->id) {
                case Role::VIVEDIA_ADMIN_ID:
                    return true;
                    break;
                case Role::VIVEDIA_STAFF_ID:
                    return true;
                    break;
                case Role::SITE_ADMIN_ID:
                    break;
                case Role::SITE_STAFF_ID:
                    break;
                default:
                    return false;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Faqs $faqs)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Faqs $faqs)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Faqs $faqs)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Faqs $faqs)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Faqs $faqs)
    {
        //
    }
}
