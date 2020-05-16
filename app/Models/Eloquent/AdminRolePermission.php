<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRolePermission
 * 
 * @property int $role_id
 * @property int $permission_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminRolePermission extends Model
{
	protected $table = 'admin_role_permissions';
	public $incrementing = false;

	protected $casts = [
		'role_id' => 'int',
		'permission_id' => 'int'
	];

	protected $fillable = [
		'role_id',
		'permission_id'
	];
}
