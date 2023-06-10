<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GroupRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\Widget;

class GroupCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Group::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group');
        $this->crud->setEntityNameStrings(__('admin.group'), __('admin.groups'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(GroupRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        Widget::add([
            'type'           => 'relation_table',
            'name'           => 'groupPrices',
            'label'          => __('admin.group_prices'),
            'per_page'       => '10',
            'backpack_crud'  => 'group-price',
            'relation_attribute' => 'group_id',
            'button_create' => true,
            'button_delete' => true,
            'columns' => [
                [
                    'label' => __('admin_fields.product'),
                    'name'  => 'product.name',
                ],
                [
                    'label' => __('admin_fields.price'),
                    'name'  => 'price',
                ],
            ],
        ])->to('after_content');
    }
}
