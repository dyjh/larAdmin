<?php


namespace App\Repositories;


use App\Http\Model\Eloquent\SystemConfig;
use App\Http\Model\Eloquent\UserInfo;
use Illuminate\Support\Facades\Redis;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class UserRepository extends BaseRepository
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
        return UserInfo::class;
    }

    /**
     * @param $data
     * @throws ValidatorException
     * @return bool|array
     */
    public function createUser($data)
    {
        unset($data['verifyCode']);
        $data['avatar'] = SystemConfig::get('baseInfo', 'default_avatar', 'http://admin.17dushu.com/vendor/presets/avatar.png');
        $data['password'] = bcrypt($data['password']);
        $user = $this->create($data);
        if ($user) {
            return $user->toArray();
        } else {
            return false;
        }
    }

    /**
     * 获取用户登录token
     *
     * @param $mobile
     * @return string
     */
    public function getToken(string $mobile) : string
    {
        $name = md5('');
        return auth()->user()->createToken($name)->accessToken;
    }


    public function loginFail(string $mobile) : string
    {
        $this->redis = Redis::connection();
        if ($restTime = $this->redis->get("login_fail_$mobile") === false) {
            $this->redis->set("login_fail_$mobile", 5);
            $this->redis->expire("login_fail_$mobile", 300);
            $msg = "用户或密码错误，您还可尝试5次";
        } else {
            if ($restTime == 0) {
                $this->redis->set("login_fail_$mobile", 0);
                $msg =  "用户或密码错误，您的账号将被冻结5分钟";
            } else {
                $this->redis->decr("login_fail_$mobile");
                $restTime--;
                $msg =  "用户或密码错误，您还可尝试{$restTime}次";
            }
        }
        $this->redis->expire("login_fail_$mobile", 300);
        return $msg;
    }
}
