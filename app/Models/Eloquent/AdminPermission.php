<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminPermission
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $http_method
 * @property string $http_path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminPermission extends Model
{
	protected $table = 'admin_permissions';

	protected $fillable = [
		'name',
		'slug',
		'http_method',
		'http_path'
	];
}
