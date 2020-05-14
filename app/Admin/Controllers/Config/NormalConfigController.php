<?php

namespace App\Admin\Controllers\Config;

use App\Common\Helper\ConstantHelper;
use App\Http\Controllers\Controller;
use App\Http\Model\Constants\Config\ConfigGroup;
use App\Http\Model\Constants\Config\ConfigType;
use App\Model\Eloquent\SystemConfig;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Form;

/**
 * @author pxwei
 * Class ConfigController
 * @package App\Admin\Controllers\Form
 */
class NormalConfigController extends Controller
{
    public function form()
    {
        $dbs = SystemConfig::orderby('id','asc')->get(['id','group','key','value','comment','title','type'])->toArray();

        $configs = [];
        $configGroup = new ConfigGroup();
        foreach ($dbs as $db){
            $name = ConstantHelper::message($configGroup, $db['group']);
            $configs[$name][] = $db;
        }
        $tab = new \Encore\Admin\Widgets\Tab();


        foreach ($configs as $k => $config){
            $form = new Form();
            $form->action("/admin/setting_form_save");

            foreach ($config as $value){
                switch ($value['type']){
                    case ConfigType::TEXT:
                        $form->text($value['group'].'____'.$value['key'],$value['title'])->help($value['comment'])->default($value['value']);
                        break;
                    case ConfigType::TEXTAREA:
                        $form->editor($value['group'].'____'.$value['key'],$value['title'])->default($value['value']);
                        break;
                    case ConfigType::TIME_PICKER:
                        $form->time($value['group'].'____'.$value['key'],$value['title'])->default($value['value']);
                        break;
                    case ConfigType::FILE:
                        $form->file($value['group'].'____'.$value['key'],$value['title'])->help('当前文件：'.$value['value']);
                        break;
                    case ConfigType::SWITCH:


                            $states = [
                                'on'  => ['value' => 1, 'text' => '开', 'color' => 'success'],
                                'off' => ['value' => 0, 'text' => '关', 'color' => 'danger'],

                            ];
                            $s = $value['value'];

                        if ($value['key']=='use_personal')
                        {
                            $s = $s==0?1:0;
                        }


                        $form->switch($value['group'].'____'.$value['key'].'____Switch',$value['title'])->help($value['comment'])->default($s)->states($states);
                        break;
                    case ConfigType::CHECKBOX:
                        $data = [];
                        $default = [];
                        foreach (json_decode($value['value'],true) as $v){
                            $data[$v['key']] =$v['text'];
                            if ($v['value'] == 1)
                                $default[] = $v['key'];

                        }

                        $form->checkbox($value['group'].'____'.$value['key'].'____checkbox',$value['title'])->options($data)->default($default)->help($value['comment'])->canCheckAll();
                        break;
                    case ConfigType::JSON:
                        $json = json_decode($value['value'],true);
                        if (!$json)
                            $form->display('json',$value['title'])->default('字段JSON解析失败');
                        else{
                            $form->fieldset($value['title'].'_配置组', function (Form $form)use ($json,$value) {
                                foreach ($json as $kk=> $v){
                                    $form->text($value['group'].'____'.$value['key'].'____J____'.$kk,$kk)->default($v);
                                }
                            });
                        }
                        break;

                    case ConfigType::JSON_ARRAY:
                        $json = json_decode($value['value'],true);
                        if (!$json)
                            $form->display('json',$value['title'])->default('数组JSON解析失败');
                        else{
                            $form->fieldset($value['title'].'_配置组', function (Form $form)use ($json,$value) {
                                foreach ($json as $kk=> $v){
                                    $form->text($value['group'].'____'.$value['key'].'____JA____'.$kk,$v['text'])->default($v['value']);
                                }
                            });
                        }

                }
            }

            $tab->add(SystemConfig::$groups[$k]??$k,$form->render());
            $form = null;



        }
        $c =  new Content();
        return $c->title("配置")->description('设置')->row($tab->render());
    }

    public function setting_form_save()
    {
        $inputs = request()->input();
        unset($inputs['_token']);
        $data = [];
        $json = [];
        $jsonArr = [];
        foreach ($inputs as $k => $v){
            $groups = explode('____',$k);
            $num = count($groups);
            if ($num< 1)
                continue;
            else if ($num === 2){
                //普通字段配置
                SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>$v]);
            }else if ($num === 3){
                if ($groups[2] == "Switch"){
                    if ($v=="on")
                        $v = 1;
                    else
                        $v = 0;

                    if ($groups[1] =='use_personal')
                    {
                        $v = $v==0?1:0;
                    }

                    SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>$v]);
                }else if ($groups[2] == "checkbox"){
                    $origin_data = SystemConfig::get($groups[0],$groups[1]);
                    $origin_data = json_decode($origin_data,true);
                    foreach ($origin_data as $kk => $datum){
                        $origin_data[$kk]['value'] =0;
                        foreach ($v as $vv){
                            if ($datum['key'] == $vv)
                                $origin_data[$kk]['value'] =1;
                        }
                    }

                    SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>json_encode($origin_data)]);
                }

            }else if ($num == 4){
                if ($groups[2]=="JA"){
                    $jsonArr[$groups[0]][$groups[1]][$groups[3]] = $v;
                }else{
                    //JSON字段配置
                    $json[$groups[0]][$groups[1]][$groups[3]] = $v;
                }

            }
        }

        if (count($json)>0){
            foreach ($json as $k =>$value){
                foreach ($value as $kk => $item){
                    SystemConfig::where(['group'=>$k,'key'=>$kk])->update(['value'=>json_encode($item)]);
                }
            }
        }

        if (count($jsonArr)>0){
            foreach ($jsonArr as $k =>$value){
                foreach ($value as $kk => $item){
                    $res = SystemConfig::where(['group'=>$k,'key'=>$kk])->first();
                    if (!$res)
                        continue;
                    $json = json_decode($res['value'],true);

                    foreach ($item as $kkk => $item2){
                        $json[$kkk]['value'] = $item2;
                        $res->update(['value'=>json_encode($json)]);



                }
            }
        }
        }

        $files = request()->file();

        foreach ($files as $key => $file){
            $groups = explode('____',$key);
            $file_name ='upload/SystemFiles/'.uniqid().'.'.$file->getClientOriginalExtension();
            file_put_contents(public_path($file_name),file_get_contents($file->getRealPath()));
            SystemConfig::where(['group'=>$groups[0],'key'=>$groups[1]])->update(['value'=>$file_name]);

        }


        admin_info('保存成功');
        return back();
    }


}
