<?php


namespace App\Http\Controllers\Api;


use App\Common\Repositories\PluginsRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


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

    }
}
