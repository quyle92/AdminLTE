<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'photo'
    ];//(1)

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = ['photo' => 'profile.png'];//(2)
}

/*
Note
 */
//(1a) nếu ko thêm "type" vào $fillable thì  UserController@store sẽ trả về $user Object mà ko có type.
//(1b) Còn photo vì là có default value nên When sending back the $user as JSON, it will not be included. Ref: https://stackoverflow.com/questions/53341436/laravel-model-create-method-returns-incomplete-fields-model-instance
//(2) this is a  fix for (1b). Ref: same as above
