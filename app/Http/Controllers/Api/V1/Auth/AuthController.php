<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Common\Constants\ErrorCode;
use App\Common\Constants\SmsEvent;
use App\Common\Helper\ConstantHelper;
use App\Common\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Repositories\UserRepository;
use App\Services\Base\SmsServer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    use APIResponseTrait;

    /**
     * 用户相关逻辑层
     *
     * @var UserRepository
     */
    private UserRepository $repository;

    /**
     * AuthController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * 用户注册
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post(
     *      path="/auth/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Get project information",
     *      description="用户注册",
     *      @OA\Parameter(
     *          name="verifyCode",
     *          description="验证码",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="nickName",
     *          description="昵称",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="mobile",
     *          description="用户手机号",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="用户密码",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="{'status':true,'status_code':0,'message':'','data':{'mobile':'18228068391','nickname':'1111','avatar':'http:\/\/admin.17dushu.com\/vendor\/presets\/avatar.png','updated_at':'2020-05-12T12:17:52.000000Z','created_at':'2020-05-12T12:17:52.000000Z','id':2}}"
     *       ),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        try {
            (new SmsServer(SmsEvent::REGISTER, $validated['mobile'], SmsServer::METHOD_VERIFY))
                ->check($validated['verifyCode']);
            $user = $this->repository->createUser($validated);
            return $this->api($user);
        } catch (\Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 用户注册
     *
     * @OA\Post(
     *      path="/auth/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Get project information",
     *      description="用户登录",
     *      @OA\Parameter(
     *          name="mobile",
     *          description="用户手机号",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          description="用户密码",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success"
     *       ),
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['mobile', 'password']);
        try {
            if (Redis::connection()->get("login_fail_{$credentials['mobile']}") === "0") {
                throw new \Exception(ConstantHelper::errMessage(ErrorCode::ACCOUNT_IS_FREEZE), ErrorCode::ACCOUNT_IS_FREEZE);
            }

            if (Auth::attempt($credentials)) {
                $token = $this->repository->getToken($credentials['mobile']);
                return $this->api([
                    'token' => "Bearer " . $token
                ]);
            } else {
                $msg = $this->repository->loginFail($credentials['mobile']);
                return $this->error(ErrorCode::MOBILE_OR_PASSWORD_INCORRECT, $msg);
            }
        } catch (\Exception $e) {

            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->api([]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return $this->api([
            'token' => 'bearer ' . $token,
            'rong_cloud_token' => auth('api')->user()->rong_cloud_token,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
