<?php

namespace App\Admin\Controllers\Config;

use App\Common\Helper\ConstantHelper;
use App\Http\Model\Constants\Config\ConfigGroup;
use App\Http\Model\Constants\Config\ConfigModel;
use App\Http\Model\Constants\Config\ConfigType;
use App\Model\Eloquent\SystemConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
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

        $grid->column('id', __('ID'))->sortable();
        $grid->column('group', __('配置分组'))
            ->display(function ($state) {
                $msg = ConstantHelper::message(ConfigGroup::class, $state);
                return "<span class='label label-default'>{$msg}</span>";
            });
        $grid->column('key', __('配置键'))->editable();
        $grid->column('value', __('配置值'))->display(function ($input) {
            $input = json_decode($input, true);
            if (is_array($input)) {
                $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
                if (empty($input)) {
                    return '<code>{}</code>';
                }
            }
            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG).'</pre>';
        });
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
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(SystemConfig::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('group', __('分组'));
        $show->field('key', __('key'));
        $show->field('value', __('value'));
        $show->field('fieldType', __('类型'))->using(['纯文本','富文本','JSON','开关','时间','文件','选择框','数组JSON']);
        $show->field('chinese', __('标签名称'));
        $show->field('comment', __('说明'));


        return $show;
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
        $form->textarea('value', __('配置内容'));
        $form->hidden('model', '模块')->value(ConfigModel::SYSTEM);
        return $form;
    }
}
