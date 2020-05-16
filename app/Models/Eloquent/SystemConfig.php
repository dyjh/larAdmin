<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemConfig
 *
 * @property string $group
 * @property string $key
 * @property array $value
 * @property int $type
 * @property string $comment
 * @property string $title
 * @property int $model
 * @property int $id
 * @property string $rule
 *
 * @package App\Models\Eloquent
 */
class SystemConfig extends Model
{
	protected $table = 'system_config';

	public $timestamps = false;

	protected $casts = [
		'value' => 'json',
		'type' => 'int',
		'model' => 'int'
	];

	protected $fillable = [
		'group',
		'key',
		'value',
		'type',
		'comment',
		'title',
		'model',
		'rule'
	];

    /**
     * @param String $group
     * @param String $key
     * @param String $default
     * @return array|String
     */
    public static function get(string $group, string $key = '', string $default = "")
    {
        $query = self::where('group', $group);
        if ($key) {
            $res = $query->where('key', $key)->first(['value'])->value ?? $default;
            return $res;
        } else {
            $res =  $query->get(['key', 'value']);
            $arr = [];
            foreach ($res as $index => $item) {
                $arr[$item['key']] = $item['value'];
            }
            return $arr;
        }
    }

    /**
     * @param String $group
     * @param String $key
     * @param String $value
     * @return int
     */
    public static function set(string $group, string $key, string $value)
    {
        $config = self::where([['group', $group], ['key', $key]])->first();
        if ($config) {
            $config->value = $value;
            $ret = $config->save();
        } else {
            $ret = self::create([
                'group' => $group,
                'key'   => $key,
                'value' => $value
            ]);
        }
        return $ret;
    }
}
