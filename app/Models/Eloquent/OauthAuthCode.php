<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OauthAuthCode
 * 
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string $scopes
 * @property bool $revoked
 * @property Carbon $expires_at
 *
 * @package App\Models\Eloquent
 */
class OauthAuthCode extends Model
{
	protected $table = 'oauth_auth_codes';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'client_id' => 'int',
		'revoked' => 'bool'
	];

	protected $dates = [
		'expires_at'
	];

	protected $fillable = [
		'user_id',
		'client_id',
		'scopes',
		'revoked',
		'expires_at'
	];
}
