<?php

namespace App\Common\Services;

use App\Common\Events\PluginWasDeleted;
use App\Common\Events\PluginWasDisabled;
use App\Common\Events\PluginWasEnabled;
use App\Common\Events\PluginWasInstall;
use App\Common\Repositories\PluginsRepository;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;

class PluginManager
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var PluginsRepository
     */
    protected $option;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Collection|null
     */
    protected $plugins;

    public function __construct(
        Application $app,
        PluginsRepository $option,
        Dispatcher $dispatcher,
        Filesystem $filesystem
    )
    {
        $this->app = $app;
        $this->option = $option;
        $this->dispatcher = $dispatcher;
        $this->filesystem = $filesystem;
    }

    /**
     * @return Collection
     */
    public function getPlugins()
    {

        if (is_null($this->plugins)) {

            $plugins = new Collection();

            $pluginDirs = $this->filesystem->directories(base_path('plugins'));

            foreach ($pluginDirs as $pluginDir) {
                if (file_exists($pluginDir . "/package.json")) {
                    // Instantiates an Plugin object using the package path and package.json file.
                    $plugin = new Plugin();
                    $plugin->setPath($pluginDir);
                    $checkInstall = $this->option->findWhere(['name' => $plugin->name])->first();
                    if ($checkInstall) {
                        $plugin->setInstalled(true);
                    }

                    $plugins->put($plugin->name, $plugin);

                }
            }

            $this->plugins = $plugins->sortBy(function ($plugin, $name) {
                return $plugin->name;
            });
        }
        return $this->plugins;
    }

    /**
     * @param int $start
     * @param int $perPage
     * @return Collection
     */
    public function getPluginsUninstall(int $start = 0, int $perPage = 0): Collection
    {
        $plugins = new Collection();

        $pluginDirs = $this->filesystem->directories(base_path('plugins'));

        foreach ($pluginDirs as $pluginDir) {
            if (file_exists($pluginDir . "/package.json")) {
                $plugin = new Plugin();
                $plugin->setPath($pluginDir);
                $checkInstall = $this->option->findWhere(['name' => $plugin->name])->first();
                if ($checkInstall) {
                    continue;
                }
                $plugins->add($plugin);

            }
        }
        if ($start && $perPage) {
            $plugins = $plugins->slice(1, 1);
        }
        return $plugins;
    }

    /**
     * Loads an Plugin with all information.
     *
     * @param string $name
     * @return Plugin|null
     */
    public function getPlugin($name)
    {
        return $this->getPlugins()->get($name);
    }

    public function getPluginId($name)
    {
        $plugin = $this->option->findWhere(['name' => $name])->first();
        return $plugin->id;
    }

    public function findPlugin($id)
    {
        return $this->getPlugins()->first(function (Plugin $plugin) use ($id) {
            if ('' === $plugin->getId()) {
                return false;
            }
            return $plugin->getId() == $id;
        });
    }

    /**
     * Enables the plugin.
     *
     * @param string $name
     */
    public function enable($name)
    {
        if (!$this->isOptionEnable($name)) {
            DB::transaction(function () use ($name) {
                $plugin = $this->getPlugin($name);
//                $enabled[] = $name;
                $this->setEnabled(1, $name);
                $plugin->setEnabled(true);
                $plugin->app()->init();
                //b$this->dispatcher->fire(new events\PluginWasEnabled($plugin));
                event(new PluginWasEnabled($plugin));
            });
        }

    }

    /**
     * Disables an plugin.
     *
     * @param string $name
     */
    public function disable($name)
    {
        $plugin = $this->getPlugin($name);

        $this->setEnabled(0, $name);

        $plugin->setEnabled(false);
        $plugin->app()->init();
        //$this->dispatcher->fire(new events\PluginWasEnabled($plugin));
        event(new PluginWasDisabled($plugin));
    }

    /**
     * Uninstalls an plugin.
     *
     * @param string $name
     */
    public function uninstall($name)
    {
        try {
            $plugin = $this->getPlugin($name);
            $this->disable($name);

            event(new PluginWasDeleted($plugin));
            $this->filesystem->deleteDirectory($plugin->getPath());
            $this->option->where('name', $name)->delete();
            // refresh plugin list
            $this->plugins = null;
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function install($name)
    {
        try {
            $plugin = $this->getPlugin($name);
            event(new PluginWasInstall($plugin));
            $check = $this->option->findWhere(['name' => $name])->first();
            if (!$check) {
                if ($this->option->insertPlugin($name)) {
                    return 'success';
                } else {
                    return 'fail';
                }
            } else {
                return 'success';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Get only enabled plugins.
     *
     * @return Collection
     */
    public function getEnabledPlugins()
    {
        $only = [];
        foreach ($this->getAll() as $key => $plugin) {
            if ($plugin['enabled'] == 1) {
                $only[] = $key;
            }
        }
        return $this->getPlugins()->only($only);
    }

    /**
     * The id's of the enabled plugins.
     *
     * @return array
     */
    public function getAll()
    {
        return (array)$this->option->getAll();

    }

    /**
     * Persist the currently enabled plugins.
     *
     * @param int $enabled
     * @param null $name
     * @return bool|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    protected function setEnabled($enabled, $name)
    {
        $check = $this->option->findWhere(['name', $name]);
        if (!$check) {
            return $this->option->insertPlugin($name);
        } else {
            return $this->option->switchPlugin($name, $enabled);
        }
    }

    /**
     * Whether the plugin is enabled.
     *
     * @param $plugin
     * @return bool
     */
    public function isEnabled($pluginName)
    {
        $plugin = $this->getPlugin($pluginName);
        if (!$plugin) {
            return false;
        }
        return $plugin->isEnabled();

    }

    private function isOptionEnable($plugin)
    {
        $plugins = $this->getAll();
        return $plugins[$plugin]['enabled'];
//        return in_array($plugin, $this->getAll());
    }

    /**
     * The plugins path.
     *
     * @return string
     */
    protected function getPluginsDir()
    {
        return $this->app->basePath() . '/Plugins';
    }

}
