<?php


namespace App\Http\Controllers\Api\V1\User;


use App\Common\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Resources\User\UserResource;

class UserCenterController extends Controller
{
    use APIResponseTrait;

    /**
     * 个人中心
     *
     * @OA\Get(
     *      path="/user/me",
     *      operationId="me",
     *      tags={"User"},
     *      summary="Get project information",
     *      description="用户获取个人信息",
     *      @OA\Response(
     *          response=200,
     *          description="success"
     *       ),
     * )
     */
    public function me()
    {
        //Api资源
        //单个用 new UserResource() 多个用 UserResource::collection()
        return $this->api(new UserResource(auth('api')->user()));
    }
}
