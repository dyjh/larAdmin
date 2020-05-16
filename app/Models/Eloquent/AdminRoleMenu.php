<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRoleMenu
 * 
 * @property int $role_id
 * @property int $menu_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminRoleMenu extends Model
{
	protected $table = 'admin_role_menu';
	public $incrementing = false;

	protected $casts = [
		'role_id' => 'int',
		'menu_id' => 'int'
	];

	protected $fillable = [
		'role_id',
		'menu_id'
	];
}
