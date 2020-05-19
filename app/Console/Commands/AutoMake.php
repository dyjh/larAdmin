<?php

namespace App\Console\Commands;

use Encore\Admin\Console\ResourceGenerator;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Reliese\Coders\Model\Factory;

class AutoMake extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auto-make {--table=} {--title=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动生成laravelAdmin所需的控制器以及模型';

    /**
     * @var \Reliese\Coders\Model\Factory
     */
    protected $models;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * 模型名称
     *
     * @var string
     */
    protected $modelName;

    /**
     * 控制器名称
     *
     * @var string
     */
    protected $controllerName;

    /**
     * @var ResourceGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Factory $models, Repository $config, Filesystem $files)
    {
        parent::__construct($files);
        $this->models = $models;
        $this->config = $config;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $table = $this->option('table');
        $fileName =  str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
        //echo $fileName;die;
        $this->modelName = "App\\Models\\Eloquent\\{$fileName}";
        $this->controllerName = "{$fileName}Controller";

        $connection = $this->config->get('database.default');
        $schema = $this->config->get("database.connections.$connection.database");

        // Check whether we just need to generate one table
        if ($table) {
            $this->models->on($connection)->create($schema, $table);
            $this->info("Model app/Models/Eloquent/{$fileName} created successfully.");
        }

        if (!$this->modelExists()) {
            $this->error('Model does not exists !');

            return false;
        }


        $this->generator = new ResourceGenerator($this->modelName);

        if (parent::handle() !== false) {
            $name = $this->controllerName;
            $path = Str::plural(Str::kebab(class_basename($this->modelName)));
            $this->insertAfterTarget(app_path('Admin/routes.php'), "    \$router->resource('/{$path}', {$name}::class);", '$router->');
            $this->info("create the following route /{$path} successfully.");
        }
        // Otherwise map the whole database
        return true;
    }

    protected function insertAfterTarget($filePath, $insertCont, $target)
    {
        if ($this->getLineNum($filePath, $insertCont)) {
            return true;
        }
        $result = null;
        $fileCont = file_get_contents($filePath);
        $targetIndex = strrpos($fileCont, $target); #查找目标字符串的坐标

        if ($targetIndex !== false) {
            #找到target的后一个换行符
            $chLineIndex = strpos(substr($fileCont, $targetIndex), "\n") + $targetIndex;
            if ($chLineIndex !== false) {
                #插入需要插入的内容
                $result = substr($fileCont, 0, $chLineIndex + 1) . $insertCont . "\n" . substr($fileCont, $chLineIndex + 1);
                $fp = fopen($filePath, "w+");
                fwrite($fp, $result);
                fclose($fp);
            }
        }
    }

    #获取某段内容的行号
    /**
     * @param $filePath
     * @param string $target
     * @param bool $first   是否再匹配到第一个字段后退出
     * @return array|int
     */
    protected function getLineNum($filePath, $target, $first = false)
    {
        $fp = fopen($filePath, "r");
        $lineNumArr = array();
        $lineNum = 0;
        while (!feof($fp)) {
            $lineNum++;
            $lineCont = fgets($fp);
            if (strstr($lineCont, $target)) {
                if($first) {
                    return $lineNum;
                } else {
                    $lineNumArr[] = $lineNum;
                }
            }
        }
        return $lineNumArr;
    }


    /**
     * @param string $modelName
     */
    protected function output($modelName)
    {
        $this->alert("laravel-admin controller code for model [{$modelName}]");

        $this->info($this->generator->generateGrid());
        $this->info($this->generator->generateShow());
        $this->info($this->generator->generateForm());
    }

    /**
     * Determine if the model is exists.
     *
     * @return bool
     */
    protected function modelExists()
    {
        $model = $this->modelName;

        if (empty($model)) {
            return true;
        }

        return class_exists($model) && is_subclass_of($model, Model::class);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace(
            [
                'DummyModelNamespace',
                'DummyTitle',
                'DummyModel',
                'DummyGrid',
                'DummyShow',
                'DummyForm',
            ],
            [
                $this->modelName,
                $this->option('title') ?: $this->modelName,
                class_basename($this->modelName),
                $this->indentCodes($this->generator->generateGrid()),
                $this->indentCodes($this->generator->generateShow()),
                $this->indentCodes($this->generator->generateForm()),
            ],
            $stub
        );
    }

    /**
     * @param string $code
     *
     * @return string
     */
    protected function indentCodes($code)
    {
        $indent = str_repeat(' ', 8);

        return rtrim($indent.preg_replace("/\r\n/", "\r\n{$indent}", $code));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {

        if ($this->modelName) {
            return __DIR__.'/stubs/controller.stub';
        }

        return __DIR__.'/stubs/blank.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('admin.route.namespace');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->controllerName);

        $this->type = $this->qualifyClass($name);

        return $name;
    }
}
