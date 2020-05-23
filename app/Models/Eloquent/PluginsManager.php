<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

/**
 * Class Plugins
 *
 * @property int $id
 * @property string $icon
 * @property string $name
 * @property string $title
 * @property string $version
 * @property string $description
 * @property int $enable
 * @property string $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Eloquent
 */
class PluginsManager extends Model
{
    public function paginate()
    {
        $perPage = Request::get('per_page', 10);

        $page = Request::get('page', 1);

        $start = ($page-1)*$perPage;

        $data = app('plugins')->getPluginsUninstall($start, $perPage)->toArray();
        extract($data);
        $total = count($data);

        $result = static::hydrate($data);

        $paginator = new LengthAwarePaginator($result, $total, $perPage, $page);

        $paginator->setPath(url()->current());

        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }

}
