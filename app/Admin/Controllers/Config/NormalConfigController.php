<?php

namespace App\Admin\Controllers\Config;

use App\Common\Helper\ConstantHelper;
use App\Http\Controllers\Controller;
use App\Models\Constants\Config\ConfigGroup;
use App\Models\Constants\Config\ConfigModel;
use App\Models\Constants\Config\ConfigType;
use App\Models\Eloquent\SystemConfig;
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
            }
        }
        admin_info('保存成功');
        return back();
    }


}
