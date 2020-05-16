<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminUserPermission
 * 
 * @property int $user_id
 * @property int $permission_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminUserPermission extends Model
{
	protected $table = 'admin_user_permissions';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'permission_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'permission_id'
	];
}
