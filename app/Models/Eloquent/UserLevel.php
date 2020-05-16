<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserLevel
 *
 * @property int $id
 * @property string $name
 * @property string $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class UserLevel extends Model
{
	use SoftDeletes;
	protected $table = 'user_level';

	protected $fillable = [
		'name'
	];
}
