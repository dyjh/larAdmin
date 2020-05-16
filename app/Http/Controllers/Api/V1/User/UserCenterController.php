<?php


namespace App\Http\Controllers\Api\V1\User;


use App\Common\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;

class UserCenterController extends Controller
{
    use APIResponseTrait;

    public function me()
    {
        //单个用 new UserResource() 多个用 UserResource::collection()
        return $this->api(new UserResource(auth('api')->user()));
    }
}
