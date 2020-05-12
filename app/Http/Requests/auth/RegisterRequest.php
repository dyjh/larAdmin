<?php


namespace App\Http\Requests\auth;


use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'mobile'     => 'required|unique:user_info,mobile|regex:/^1[34578][0-9]{9}$/',
            'password'   => 'required',
            'nickname'   => 'required|max:10',
            'verifyCode' => 'required'
        ];
    }
}
