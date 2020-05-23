<?php


namespace App\Admin\Controllers\Plugins;


use App\Http\Controllers\Controller;
use App\Models\Eloquent\Plugins;
use App\Models\Eloquent\PluginsManager;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;

class ManagerController extends Controller
{
    public function index(Content $content)
    {
        $server = str_replace(request()->getRequestUri(), '', request()->url());
        $tab = new Tab();
        //$grid = new Grid(new Plugins());
        $grid = new Grid(new PluginsManager());
        $grid->disableBatchActions();
        $grid->disableActions();
        $grid->column('icon', __('plugins.Icon'))->image($server, 50, 50)->style('text-align:center');
        $grid->column('name', __('plugins.Name'))->style('text-align:center');
        $grid->column('title', __('plugins.Title'))->style('text-align:center');
        $grid->column('version', __('plugins.Version'))->style('text-align:center');
        $grid->column('description', __('plugins.Description'))->style('text-align:center');
        $grid->column('操作')
            ->display(function () {
                $name = $this->getAttribute('title');

                $buttonName = '安装';

                $link = admin_url('plugins-manager/install');
                $script = <<<JS
$('.gen').on('click', function () {

    var name = $(this).data('name');

    swal({
        title: "确认要安装应用{$name}吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消"
    }).then(function (choose) {
        console.log(choose);
        if (choose.value === true) {

            $.post('{$link}/' + name, {
                _token: LA.token,
            },
            function(data){
                $.pjax.reload('#pjax-container');
                if (data.status) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            });
        }
    });
});
JS;

                Admin::script($script);
                return "<button class='btn btn-primary btn-xs gen' data-name='{$this->getAttribute('name')}'>$buttonName</button>";
            })->style('text-align:center');
        $tab->add('未安裝插件', $grid->render());

        return $content->title("配置")->description('设置')->row($tab->render());
    }

    public function install()
    {

    }
}
