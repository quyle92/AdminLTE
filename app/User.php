<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'photo'
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

    protected $attributes = ['photo' => 'profile.png'];//(2)php

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

/*
Note
 */
//(1a) nếu ko thêm "user_id" vào $fillable thì  UserController@store sẽ trả về $user Object mà ko có user_id.
//(1b) Còn photo vì là có default value nên When sending back the $user as JSON, it will not be included. Ref: https://stackoverflow.com/questions/53341436/laravel-model-create-method-returns-incomplete-fields-model-instance
//(2) this is a  fix for (1b). Ref: same as above
