<?php


namespace App\Admin\Controllers\Plugins;


use App\Http\Controllers\Controller;
use App\Models\Eloquent\Plugins;
use App\Models\Eloquent\PluginsManager;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;
use Illuminate\Http\Request;

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

                $link = admin_url('/plugins-manager/install');
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

            $.post('{$link}', {
                _token: LA.token,
                'name': name
            },
            function(data){
                $.pjax.reload('#pjax-container');
                if (data.code == 200) {
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


        $grid2 = new Grid(new Plugins());
        $grid2->disableBatchActions();
        $grid2->disableActions();
        $grid2->column('icon', __('plugins.Icon'))->image($server, 50, 50)->style('text-align:center');
        $grid2->column('name', __('plugins.Name'))->style('text-align:center');
        $grid2->column('title', __('plugins.Title'))->style('text-align:center');
        $grid2->column('version', __('plugins.Version'))->style('text-align:center');
        $grid2->column('description', __('plugins.Description'))->style('text-align:center');
        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        ];
        $grid2->column('status', __('plugins.Status'))->switch($states);
        $grid2->column('操作')
            ->display(function () {
                $name = $this->getAttribute('title');

                $buttonName = '卸载';

                $link = admin_url('/plugins-manager/uninstall');
                $script = <<<JS
$('.gen').on('click', function () {

    var name = $(this).data('name');

    swal({
        title: "确认要卸载应用{$name}吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消"
    }).then(function (choose) {
        console.log(choose);
        if (choose.value === true) {

            $.post('{$link}', {
                _token: LA.token,
                'name': name
            },
            function(data){
                $.pjax.reload('#pjax-container');
                if (data.code == 200) {
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

        $tab->add('已安裝', $grid2->render());
        $tab->add('未安裝', $grid->render());
        return $content->title("配置")->description('设置')->row($tab->render());
    }

    public function install(Request $request)
    {
        if ($request->has('name')) {
            $res = app('plugins')->install($request->input('name'));
            return response()->json([
                'code' => $res == 'success' ? 200:500,
                'message' => $res
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'message' => '参数错误'
            ]);
        }
    }

    public function uninstall(Request $request)
    {
        if ($request->has('name')) {
            $res = app('plugins')->uninstall($request->input('name'));
            return response()->json([
                'code' => $res == 'success' ? 200:500,
                'message' => $res
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'message' => '参数错误'
            ]);
        }
    }

    public function update($pluginId)
    {
        $status = request()->input('status');
        $plugin = Plugins::where('id', $pluginId)->first();
        if ($status == 'on') {
            app('plugins')->enable($plugin->name);
        }

        if ($status == 'off') {
            app('plugins')->disable($plugin->name);
        }

        return response()->json(['status'=> true,'message' => '修改成功']);
    }
}
