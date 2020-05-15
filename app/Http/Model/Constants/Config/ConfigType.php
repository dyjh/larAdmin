<?php


namespace App\Http\Model\Constants\Config;


use App\Common\Constants\Constant;
use App\Model\Eloquent\SystemConfig;
use Encore\Admin\Form\Field;
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
    const NUMBER        = 15;
    const MONEY         = 16;
    const RATE          = 17;
    private static $saveType = [
        'normal', 'selectList', 'json', 'jsonArr', 'file', 'switch'
    ];

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
        self::NUMBER        => '数字输入框',
        self::MONEY         => '货币输入框',
        self::RATE          => '比例输入框',
    ];



    /**
     * 文本框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_1(Form &$form, array $value) : void
    {
        $form_ready = $form->text($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->default($value['value']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 文本域
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_2(Form &$form, array $value) : void
    {
        $form_ready = $form->textarea($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->rows(1)->default($value['value']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * JSON配置组
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_3(Form &$form, array $value) : void
    {
        $json = json_decode($value['value'], true);
        if (!$json) {
            $form->display('json', $value['title'])->default('字段JSON解析失败');
        } else {
            $form->fieldset($value['title'] . '_配置组', function (Form $form) use ($json, $value) {
                foreach ($json as $k => $v){
                    $form->text($value['group'] . '-' . $value['key'] . '-' . $k . "-" . self::$saveType[2], $k)->default($v);
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
    public static function form_4(Form &$form, array $value) : void
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
        $form_ready = $form->switch($value['group'] . '-' . $value['key'] . "-" . self::$saveType[5], $value['title'])->default((int)$s)->states($states);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 时间选择
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_5(Form &$form, array $value) : void
    {
        $form_ready = $form->time($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->default($value['value']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 文件上传
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_6(Form &$form, array $value) : void
    {
        $field = $value['group'] . '-' . $value['key'] . "-" . self::$saveType[4];
        $form->fill(["$field" => file_url(json_decode($value['value']))]);
        $form_ready = $form->file($value['group'] . '-' . $value['key'] . "-" . self::$saveType[4], $value['title']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 下拉框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_7(Form &$form, array $value) : void
    {
        $data = [];
        $default = [];
        foreach (json_decode($value['value'], true) as $v){
            $data[$v['key']] = $v['text'];
            if ($v['value'] == 1) {
                $default = $v['key'];
            }
        }
        $form_ready = $form->select($value['group'] . '-' . $value['key'] . "-" . self::$saveType[1], $value['title'])->options($data)->default($default);
        self::form_info_set($form_ready, $value);
    }

    /**
     * json数组
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_8(Form &$form, array $value) : void
    {
        $json = json_decode($value['value'], true);
        if (!$json) {
            $form->display('json', $value['title'])->default('数组JSON解析失败');
        } else {
            $form->fieldset($value['title'] . '_配置组', function (Form $form) use ($json, $value) {
                foreach ($json as $k => $v){
                    $form->text($value['group'] . '-' . $value['key'] . '-' . $k . "-" . self::$saveType[3], $v['text'])->default($v['value']);
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
    public static function form_9(Form &$form, array $value) : void
    {
        $field = $value['group'] . '-' . $value['key'] . "-" . self::$saveType[4];
        $form->fill(["$field" => file_url(json_decode($value['value']))]);
        $form_ready = $form->image($value['group'] . '-' . $value['key'] . "-" . self::$saveType[4], $value['title'])
            ->thumbnail('small', $width = 300, $height = 300)
            ->removable();
        self::form_info_set($form_ready, $value);
    }

    /**
     * 多图上传
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_10(Form &$form, array $value) : void
    {
        $field = $value['group'] . '-' . $value['key'] . "-" . self::$saveType[4];
        $imgArr = json_decode($value['value'], true);
        foreach ($imgArr as $key => $img) {
            $imgArr[$key] = file_url($img);
        }
        $form->fill(["$field" => $imgArr]);
        $form_ready = $form->multipleImage($value['group'] . '-' . $value['key'] . "-" . self::$saveType[4], $value['title'])->removable();
        self::form_info_set($form_ready, $value);
    }

    /**
     * 复选框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_11(Form &$form, array $value) : void
    {
        $data = [];
        $default = [];
        foreach (json_decode($value['value'], true) as $v){
            $data[$v['key']] = $v['text'];
            if ($v['value'] == 1) {
                $default[] = $v['key'];
            }
        }
        $form_ready = $form->checkbox($value['group'] . '-' . $value['key'] . "-" . self::$saveType[1], $value['title'])->options($data)->default($default)->canCheckAll();
        self::form_info_set($form_ready, $value);
    }

    /**
     * 富文本框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_12(Form &$form, array $value) : void
    {
        $form->ueditor($value['group'].'-'.$value['key'] . "-" . self::$saveType[0], $value['title'])->default($value['value']);
    }

    /**
     * 单选框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_13(Form &$form, array $value) : void
    {
        $data = [];
        $default = [];
        foreach (json_decode($value['value'], true) as $v){
            $data[$v['key']] = $v['text'];
            if ($v['value'] == 1) {
                $default = $v['key'];
            }
        }
        $form_ready = $form->radio($value['group'] . '-' . $value['key'] . "-" . self::$saveType[1], $value['title'])->options($data)->default($default);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 日期选择
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_14(Form &$form, array $value) : void
    {
        $form_ready = $form->date($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->default($value['value']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 数字框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_15(Form &$form, array $value) : void
    {
        $form_ready = $form->number($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->default($value['value']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 金额框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_16(Form &$form, array $value) : void
    {
        $data = json_decode($value['value'], true);
        $form_ready = $form->currency($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->default($data['money'])->symbol($data['unit']);
        self::form_info_set($form_ready, $value);
    }

    /**
     * 比例框
     *
     * @param Form $form
     * @param array $value
     */
    public static function form_17(Form &$form, array $value) : void
    {
        $form_ready = $form->rate($value['group'] . '-' . $value['key'] . "-" . self::$saveType[0], $value['title'])->default($value['value']);
        self::form_info_set($form_ready, $value);
    }

    private static function form_info_set($form, array $value)
    {
        if (!empty($value['comment'])) {
            $form->help($value['comment']);
        }
        if (!empty($value['rule'])) {
            $form->rules($value['rule']);
        }
    }

    public static function save_normal($groups, $v)
    {
        SystemConfig::where(['group'=>$groups[0], 'key'=>$groups[1]])->update(['value'=>$v]);
    }

    public static function save_selectList($groups, $v)
    {
        $origin_data = SystemConfig::get($groups[0], $groups[1]);
        $origin_data = json_decode($origin_data, true);
        foreach ($origin_data as $kk => $datum){
            $origin_data[$kk]['value'] = 0;
            if (is_array($v)) {
                foreach ($v as $item){
                    if ($datum['key'] == $item) {
                        $origin_data[$kk]['value'] = 1;
                        continue;
                    }
                }
            }
            if (is_string($v) && $datum['key'] == $v) {
                $origin_data[$kk]['value'] = 1;
                continue;
            }
        }
        SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>json_encode($origin_data)]);
    }

    public static function save_json($groups, $v)
    {
        $json[$groups[0]][$groups[1]][$groups[2]] = $v;
        foreach ($json as $k =>$value){
            foreach ($value as $kk => $item){
                SystemConfig::where(['group'=>$k,'key'=>$kk])->update(['value'=>json_encode($item)]);
            }
        }
    }

    public static function save_jsonArr($groups, $v)
    {
        $jsonArr[$groups[0]][$groups[1]][$groups[2]] = $v;
        foreach ($jsonArr as $k => $value) {
            foreach ($value as $kk => $item) {
                $res = SystemConfig::where(['group' => $k, 'key' => $kk])->first();
                if (!$res) {
                    continue;
                }
                $json = json_decode($res['value'],true);

                foreach ($item as $kkk => $item2){
                    $json[$kkk]['value'] = $item2;
                    $res->update(['value'=>json_encode($json)]);
                }
            }
        }

    }

    public static function save_file()
    {
        $files = request()->file();
        foreach ($files as $key => $file) {
            $groups = explode('-', $key);
            if (is_array($file)) {
                $path = [];
                foreach ($file as $file_item) {
                    $path[] = $file_item->store($groups[2], env('FILESYSTEM_DRIVER', 'local'));
                }
            } else {
                $path = $file->store($groups[2], env('FILESYSTEM_DRIVER', 'local'));
            }

            SystemConfig::where(['group' => $groups[0], 'key' => $groups[1]])->update(['value' => json_encode($path)]);
        }
    }

    public static function save_switch($groups, $v)
    {
        if ($v == "on") {
            $v = 1;
        } else {
            $v = 0;
        }
        SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>$v]);
    }
}
