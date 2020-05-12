<?php

namespace App\Model\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserInfo extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'user_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'real_name', 'sex', 'mobile', 'avatar', 'id_card', 'nickname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return UserInfo
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
