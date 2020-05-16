<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class UserInfo
 *
 * @property int $id
 * @property string $mobile
 * @property string $nickname
 * @property string $password
 * @property string $email
 * @property string $id_card
 * @property string $real_name
 * @property int $sex
 * @property string $avatar
 * @property int $consecutive_login_days
 * @property string $last_login_ip
 * @property int $login_at
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @property int $level_id
 *
 * @package App\Models\Eloquent
 */
class UserInfo extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

	protected $table = 'user_info';

	protected $casts = [
		'sex' => 'int',
		'consecutive_login_days' => 'int',
		'login_at' => 'int',
		'level_id' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'mobile',
		'nickname',
		'password',
		'email',
		'id_card',
		'real_name',
		'sex',
		'avatar',
		'level_id'
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

    /**
     * 关联user_level
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(UserLevel::class, 'level_id', 'id');
    }
}
