<?php

namespace App\Admin\Controllers\Config;

use App\Common\Helper\ConstantHelper;
use App\Models\Constants\Config\ConfigGroup;
use App\Models\Constants\Config\ConfigModel;
use App\Models\Constants\Config\ConfigType;
use App\Models\Eloquent\SystemConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Arr;

/**
 * @author pxwei
 * Class ConfigController
 * @package App\Admin\Controllers\City
 */
class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '配置管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SystemConfig());
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });
        $grid->column('id', __('ID'))->sortable();
        $grid->column('group', __('配置分组'))
            ->display(function ($state) {
                $msg = ConstantHelper::message(ConfigGroup::class, $state);
                return "<span class='label label-default'>{$msg}</span>";
            });
        $grid->column('key', __('配置键'))->editable();
        $grid->column('value', __('配置值'))->display(function (){
            if (strlen($this->value)>30){
                return mb_substr($this->value,0,20).'...';
            }
            return $this->value;
        })->modal('配置值', function () {
            $input = json_decode($this->value, true);
            if (is_array($input)) {
                $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
                if (empty($input)) {
                    return '<code>{}</code>';
                }
            }
            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG).'</pre>';
        })->copyable();
        $grid->column('title', __('配置名称'))->editable();
        $grid->column('type', __('配置类型'))
            ->display(function ($state) {
                $msg = ConstantHelper::message(ConfigType::class, $state);
                return "<span class='label label-success'>{$msg}</span>";
            });
        $grid->column('comment', __('配置说明'))->editable();

        $grid->filter(function (Grid\Filter $filter){
            $filter->disableIdFilter();
            $filter->column(1/2, function ($filter) {
                $filter->like('group','配置分组');
                $filter->like('key','配置键');

            });
            $filter->column(1/2, function ($filter) {
                $filter->like('value','配置值');
            });
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SystemConfig);
        $form->display('id', __('ID'));
        $form->select('group', __('配置分组'))->options('/admin/base/getConstantOption?constant_class=' . ConfigGroup::class);
        $form->text('key', __('配置键名'));
        $form->text('title', __('设置名称'))->help('中文标签名词，方便用户查看');
        $form->select('type', __('配置类型'))->options('/admin/base/getConstantOption?constant_class=' . ConfigType::class);
        $form->text('comment', __('配置描述'));
        $form->text('rule', __('配置表单提交校验规则'))->help('内容不超过100个字符，以英文键盘|字符间隔');
        $form->textarea('value', __('配置内容'))->help('其中 单选框、多选框、下拉框 的格式为[{"key": "wechat", "text": "微信零钱", "value": 0}]，其中key为value，text为显示的名称，value为是否选中')
        ->help('金额输入框的格式为{"money":0,"unit":"单位"}');
        $form->hidden('model', '模块')->value(ConfigModel::SYSTEM);
        return $form;
    }
}
