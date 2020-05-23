<?php


namespace App\Common\Repositories;


use app\common\models\Option;
use App\Models\Eloquent\Plugins;
use App\Models\Eloquent\SystemConfig;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class PluginsRepository extends BaseRepository
{
    /**
     * @var \Redis|Object
     */
    private Object $redis;


    /**
     * @inheritDoc
     */
    public function model()
    {
        // TODO: Implement model() method.
        return Plugins::class;
    }

    /**
     * @param string $name
     * @param bool $enable
     * @return mixed
     */
    public function switchPlugin(string $name, bool $enable)
    {
        return $this->findWhere(['name' => $name])->update(['enabled' => $enable]);
    }

    public function editDisable($id)
    {
        return $this->delete($id);
    }

    /**
     * @param string $name
     * @return bool|mixed
     * @throws ValidatorException
     */
    public function insertPlugin(string $name)
    {
        $configPath = app_path("plugins/$name/package.json");
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            unset($config['config']);
            unset($config['namespace']);
            $insertData = $config;
            $insertData['enabled'] = 0;
            return $this->create($insertData);
        }
        return false;
    }

    public function getAll()
    {

        $plugins = $this->all();
        $arr = [];
        foreach ($plugins as $plugin) {
            $arr[$plugin['name']] = $plugin;
        }
        return $arr;
    }
}
