<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminOperationLog
 * 
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property string $method
 * @property string $ip
 * @property string $input
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class AdminOperationLog extends Model
{
	protected $table = 'admin_operation_log';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'path',
		'method',
		'ip',
		'input'
	];
}
