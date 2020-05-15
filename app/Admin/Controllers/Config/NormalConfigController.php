<?php

namespace App\Admin\Controllers\Config;

use App\Common\Helper\ConstantHelper;
use App\Http\Controllers\Controller;
use App\Http\Model\Constants\Config\ConfigGroup;
use App\Http\Model\Constants\Config\ConfigModel;
use App\Http\Model\Constants\Config\ConfigType;
use App\Model\Eloquent\SystemConfig;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Form;

/**
 * @author richod
 * Class ConfigController
 * @package App\Admin\Controllers\Form
 */
class NormalConfigController extends Controller
{
    public function form()
    {
        $dbs = SystemConfig::where('model', ConfigModel::SYSTEM)->orderby('id','asc')->get()->toArray();
        $configs = [];
        $configGroup = ConfigGroup::class;
        foreach ($dbs as $db){
            $name = ConstantHelper::message($configGroup, $db['group']);
            $configs[$name][] = $db;
        }
        $tab = new \Encore\Admin\Widgets\Tab();
        foreach ($configs as $k => $config){
            $form = new Form();
            $form->action("/admin/setting_form_save");
            foreach ($config as $value) {
                $method = "form_{$value['type']}";
                ConfigType::$method($form, $value);
            }
            $tab->add(SystemConfig::$groups[$k] ?? $k, $form->render());
            $form = null;
        }
        $c =  new Content();
        return $c->title("配置")->description('设置')->row($tab->render());
    }

    public function settingFormSave()
    {
        $inputs = request()->post();
        unset($inputs['_token']);
        $data = [];
        $json = [];
        $jsonArr = [];
        foreach ($inputs as $k => $v){
            $groups = explode('-',$k);
            $num = count($groups);
            $method = "save_{$groups[$num - 1]}";
            dd($method);
            ConfigType::$method($groups, $v);
        }
        $files = request()->file();
        if ($files) {
            foreach ($files as $k => $v){
                $groups = explode('-',$k);
                $num = count($groups);
                $method = "save_{$groups[$num - 1]}";
                ConfigType::$method($groups, $v);
                /*if ($num < 1) {
                    continue;
                } else if ($num === 2) {
                    //普通字段配置
                    SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>$v]);
                } else if ($num === 3){
                    if ($groups[2] == "Switch") {
                        if ($v == "on") {
                            $v = 1;
                        } else {
                            $v = 0;
                        }
                        SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>$v]);
                    } else if ($groups[2] == "checkbox" || $groups[2] == "radio" || $groups[2] == "select") {
                        $origin_data = SystemConfig::get($groups[0], $groups[1]);
                        $origin_data = json_decode($origin_data, true);
                        foreach ($origin_data as $kk => $datum){
                            $origin_data[$kk]['value'] =0;
                            foreach ($v as $vv){
                                if ($datum['key'] == $vv) {
                                    $origin_data[$kk]['value'] = 1;
                                }
                            }
                        }
                        SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>json_encode($origin_data)]);
                    }

                }else if ($num == 4){
                    if ($groups[2] == "JA"){
                        $jsonArr[$groups[0]][$groups[1]][$groups[3]] = $v;
                    }else{
                        //JSON字段配置
                        $json[$groups[0]][$groups[1]][$groups[3]] = $v;
                    }
                }*/
            }
        }
        dd(111);
        /*if (count($json)>0){
            foreach ($json as $k =>$value){
                foreach ($value as $kk => $item){
                    SystemConfig::where(['group'=>$k,'key'=>$kk])->update(['value'=>json_encode($item)]);
                }
            }
        }

        if (count($jsonArr) > 0) {
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

        $files = request()->file();

        foreach ($files as $key => $file) {
            $groups = explode('____', $key);
            $file_name ='upload/SystemFiles/' . uniqid() . '.' . $file->getClientOriginalExtension();
            file_put_contents(public_path($file_name), file_get_contents($file->getRealPath()));
            SystemConfig::where(['group' => $groups[0], 'key' => $groups[1]])->update(['value' => $file_name]);
        }*/


        admin_info('保存成功');
        return back();
    }


}
