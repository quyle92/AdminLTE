<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->permission = json_decode( Auth::user()->role->permission );
        $this->getUserResourcePermission();
    }

    public function getUserResourcePermission()
    {   

        $this->userResource = array_filter( $this->permission, function( $item ) {
            return $item->resourceName === 'User';
        });

        $this->userResource = reset($this->userResource); // (1)

    }

    public function view(User $user)
    {   
        return $this->userResource->read;
    }

    public function create(User $user)
    {   
        return $this->userResource->write;
    }

    public function update(User $user, Post $post)
    {  
        return $this->userResource->update;
    }

    public function delete(User $user, Post $post)
    {  
        return $this->userResource->delete;
    }
}

/*
Note
 */
//(1):remove outer arrray. Option 2: array_shift($outer_array)
//Ref: https://stackoverflow.com/questions/5009759/removing-an-outer-array