<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Plugins
 *
 * @property int $id
 * @property string $icon
 * @property string $name
 * @property string $title
 * @property string $version
 * @property string $description
 * @property int $enabled
 * @property string $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class Plugins extends Model
{
	protected $table = 'plugins';

	protected $casts = [
		'enable' => 'int'
	];

	protected $fillable = [
		'icon',
		'name',
		'title',
		'version',
		'description',
		'enabled'
	];
}
