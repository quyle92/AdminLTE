<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user)
    {   
        return $user->type === 'admin';
    }

    public function create(User $user)
    {   
        return Auth::check();
    }

    public function update(User $user, Post $post)
    {  
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post)
    {  
        return $user->id === $post->user_id;
    }
}
