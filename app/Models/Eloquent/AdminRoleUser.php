<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRoleUser
 * 
 * @property int $role_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminRoleUser extends Model
{
	protected $table = 'admin_role_users';
	public $incrementing = false;

	protected $casts = [
		'role_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'role_id',
		'user_id'
	];
}
