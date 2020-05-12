<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="OpenApi",
     *      description="项目前端api",
     *      @OA\Contact(
     *          email="953068028@qq.com"
     *      ),
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     )
     * )
     */
    /**
     *  @OA\Server(
     *      url="https://local.text.com/api/v1",
     *      description="测试服务器"
     * )
     */
}
