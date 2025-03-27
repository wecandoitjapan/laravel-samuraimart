<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'));
        $grid->column('price', __('Price'))->sortable();
        // 一覧画面ではカテゴリーIDではなく、カテゴリー名を表示する
        $grid->column('category.name', __('Category Name'));
        $grid->column('image', __('Image'))->image();
        // お勧め
        $grid->column('recommend_flag', __('Recommend Flag'));
        // 送料計算
        $grid->column('carriage_flag', __('Carriage Flag'));
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        $grid->filter(function($filter) {
            $filter->like('name', '商品名');
            $filter->like('description', '商品説明');
            $filter->between('price', '金額');
            $filter->in('category_id', 'カテゴリー')->multipleSelect(Category::all()->pluck('name', 'id'));
            // おすすめ
            $filter->equal('recommend_flag', 'おすすめフラグ')->select(['0' => 'false', '1' => 'true']);
            // 送料計算
            $filter->equal('carriage_flag', '送料フラグ')->select(['0' => 'false', '1' => 'true']);
        });

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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        // 表示画面ではカテゴリーIDではなく、カテゴリー名を表示する
        $show->field('category.name', __('Category Name'));
        $show->field('image', __('Image'))->image();
        // お勧め
        $show->field('recommend_flag', __('Recommend Flag'));
        // 送料
        $show->field('carriage_flag', __('Carriage Flag'));
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
        $form = new Form(new Product());

        $form->text('name', __('Name'));
        $form->textarea('description', __('Description'));
        $form->number('price', __('Price'));
        // 存在するカテゴリー名から選択
        $form->select('category_id', __('Category Name'))->options(Category::all()->pluck('name', 'id'));
        $form->image('image', __('Image'));
        // おすすめ
        $form->switch('recommend_flag', __('Recommend Flag'));
        // 送料
        $form->switch('carriage_flag', __('Carriage Flag'));

        return $form;
    }
}
