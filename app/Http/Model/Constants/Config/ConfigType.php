<?php


namespace App\Http\Model\Constants\Config;


use App\Common\Constants\Constant;
use Encore\Admin\Widgets\Form;

class ConfigType extends Constant
{
    const TEXT          = 1;
    const TEXTAREA      = 2;
    const JSON          = 3;
    const SWITCH        = 4;
    const TIME_PICKER   = 5;
    const FILE          = 6;
    const SELECT        = 7;
    const JSON_ARRAY    = 8;
    const IMAGE         = 9;
    const IMAGES        = 10;
    const CHECKBOX      = 11;
    const TEXTAREA_RICH = 12;
    const RADIO         = 13;
    const DATE          = 14;

    public static $_msg = [
        self::TEXT          => '纯文本',
        self::TEXTAREA      => '文本域',
        self::TEXTAREA_RICH => '富文本',
        self::JSON          => 'JSON',
        self::SWITCH        => '开关',
        self::TIME_PICKER   => '时间选择器',
        self::FILE          => '文件上传',
        self::SELECT        => '下拉框',
        self::JSON_ARRAY    => 'JSON数组',
        self::IMAGE         => '单图上传',
        self::IMAGES        => '多图上传',
        self::CHECKBOX      => '多选框',
        self::RADIO         => '单选框',
        self::DATE          => '日期选择器',
    ];

    /**
     * 文本框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_1(Form &$form, array $value)
    {
        $form->text($value['group'].'____'.$value['key'],$value['title'])->help($value['comment'])->default($value['value']);
    }

    /**
     * 文本域
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_2(Form &$form, array $value)
    {
        $form->textarea($value['group'].'____'.$value['key'],$value['title'])->help($value['comment'])->rows(1)->default($value['value']);
    }

    /**
     * JSON配置组
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_3(Form &$form, array $value)
    {
        $json = json_decode($value['value'], true);
        if (!$json) {
            $form->display('json', $value['title'])->default('字段JSON解析失败');
        } else {
            $form->fieldset($value['title'] . '_配置组', function (Form $form) use ($json, $value) {
                foreach ($json as $kk => $v){
                    $form->text($value['group'] . '____' . $value['key'] . '____J____' . $kk, $kk)->default($v);
                }
            });
        }
    }

    /**
     * 开关
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_4(Form &$form, array $value)
    {
        $states = [
            'on'  => ['value' => 1, 'text' => '开', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '关', 'color' => 'danger'],
        ];
        $s = $value['value'];
        if ($value['key'] == 'use_personal') {
            //$s = $s == 0 ? 1 : 0;
            //$s = !$s ? 1 : 0;
            $s = !$s;
        }
        $form->switch($value['group'] . '____' . $value['key'] . '____Switch', $value['title'])->help($value['comment'])->default((int)$s)->states($states);
    }

    /**
     * 时间选择
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_5(Form &$form, array $value)
    {
        $form->time($value['group'] . '____' . $value['key'], $value['title'])->default($value['value']);
    }

    /**
     * 文件上传
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_6(Form &$form, array $value)
    {
        $form->file($value['group'] . '____' . $value['key'], $value['title'])->help('当前文件：' . $value['value']);
    }

    /**
     * 下拉框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_7(Form &$form, array $value)
    {
        $data = [];
        $default = [];
        foreach (json_decode($value['value'], true) as $v){
            $data[$v['key']] = $v['text'];
            if ($v['value'] == 1) {
                $default[] = $v['key'];
            }
        }
        $form->select($value['group'] . '____' . $value['key'] . '____select', $value['title'])->options($data)->default($default)->help($value['comment']);
    }

    /**
     * json数组
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_8(Form &$form, array $value)
    {
        $json = json_decode($value['value'], true);
        if (!$json) {
            $form->display('json', $value['title'])->default('数组JSON解析失败');
        } else {
            $form->fieldset($value['title'] . '_配置组', function (Form $form) use ($json, $value) {
                foreach ($json as $kk=> $v){
                    $form->text($value['group'] . '____' . $value['key'] . '____JA____' . $kk, $v['text'])->default($v['value']);
                }
            });
        }
    }

    /**
     * 单图上传
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_9(Form &$form, array $value)
    {
        $form->image($value['group'] . '____' . $value['key'], $value['title'])
            ->thumbnail('small', $width = 300, $height = 300)
            ->removable();
    }

    /**
     * 多图上传
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_10(Form &$form, array $value)
    {
        $form->multipleImage($value['group'] . '____' . $value['key'], $value['title'])->removable();
    }

    /**
     * 复选框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_11(Form &$form, array $value)
    {
        $data = [];
        $default = [];
        foreach (json_decode($value['value'], true) as $v){
            $data[$v['key']] = $v['text'];
            if ($v['value'] == 1) {
                $default[] = $v['key'];
            }
        }
        $form->checkbox($value['group'] . '____' . $value['key'] . '____checkbox', $value['title'])->options($data)->default($default)->help($value['comment'])->canCheckAll();
    }

    /**
     * 富文本框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_12(Form &$form, array $value)
    {
        $form->editor($value['group'].'____'.$value['key'],$value['title'])->default($value['value']);
    }

    /**
     * 单选框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_13(Form &$form, array $value)
    {
        $data = [];
        $default = [];
        foreach (json_decode($value['value'], true) as $v){
            $data[$v['key']] = $v['text'];
            if ($v['value'] == 1) {
                $default[] = $v['key'];
            }
        }
        $form->radio($value['group'] . '____' . $value['key'] . '____checkbox', $value['title'])->options($data)->default($default)->help($value['comment']);
    }

    /**
     * 日期选择
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_14(Form &$form, array $value)
    {
        $form->date($value['group'] . '____' . $value['key'], $value['title'])->default($value['value']);
    }
}
