<?php

namespace App\Admin\Controllers;

use App\Models\Eloquent\Plugins;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PluginsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Eloquent\Plugins';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Plugins());

        $grid->column('id', __('Id'));
        $grid->column('icon', __('Icon'));
        $grid->column('name', __('Name'));
        $grid->column('title', __('Title'));
        $grid->column('version', __('Version'));
        $grid->column('description', __('Description'));
        $grid->column('enable', __('Enable'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Plugins::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('icon', __('Icon'));
        $show->field('name', __('Name'));
        $show->field('title', __('Title'));
        $show->field('version', __('Version'));
        $show->field('description', __('Description'));
        $show->field('enable', __('Enable'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Plugins());

        $form->text('icon', __('Icon'));
        $form->text('name', __('Name'));
        $form->text('title', __('Title'));
        $form->text('version', __('Version'));
        $form->text('description', __('Description'));
        $form->switch('enable', __('Enable'));

        return $form;
    }
}
