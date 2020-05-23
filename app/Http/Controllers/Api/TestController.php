<?php


namespace App\Http\Controllers\Api;


use App\Common\Repositories\PluginsRepository;


class TestController
{

    /**
     * @var PluginsRepository
     */
    public $repository;

    public function __construct(PluginsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        dd(app('plugins')->getEnabledPlugins());
    }
}
