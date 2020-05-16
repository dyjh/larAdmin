<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminMenu
 * 
 * @property int $id
 * @property int $parent_id
 * @property int $order
 * @property string $title
 * @property string $icon
 * @property string $uri
 * @property string $permission
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminMenu extends Model
{
	protected $table = 'admin_menu';

	protected $casts = [
		'parent_id' => 'int',
		'order' => 'int'
	];

	protected $fillable = [
		'parent_id',
		'order',
		'title',
		'icon',
		'uri',
		'permission'
	];
}
