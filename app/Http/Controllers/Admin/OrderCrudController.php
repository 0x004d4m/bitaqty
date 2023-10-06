<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\Widget;

class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Order::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/order');
        $this->crud->setEntityNameStrings(__('admin.order'), __('admin.orders'));
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name'     => 'id',
            'label'    => __('admin_fields.order'),
            'type'     => 'text',
        ]);
        $this->crud->addColumn([
            'name'     => 'userable_type',
            'label'    => __('admin_fields.userable_type'),
            'type'     => 'closure',
            'function' => function ($entry) {
                return str_replace("App\\Models\\", "", $entry->userable_type);
            }
        ]);
        $this->crud->addColumn('userable_id', [
            'label' => __('admin_fields.user'),
            'type' => "select",
            'name' => 'userable_id',
            'entity' => 'userable',
            'attribute' => "name",
        ]);
        $this->crud->setColumnDetails('userable_id', [
            'label' => __('admin_fields.user'),
            'type' => "select",
            'name' => 'userable_id',
            'entity' => 'userable',
            'attribute' => "name",
        ]);
        $this->crud->column('quantity')->label(__('admin_fields.quantity'))->type('number');
        $this->crud->column('device_name')->label(__('admin_fields.device_name'))->type('text');
        $this->crud->column('price')->label(__('admin_fields.price'))->type('double');
        $this->crud->column('profit')->label(__('admin_fields.profit'))->type('number');
        $this->crud->column('credit_before')->label(__('admin_fields.credit_before'))->type('double');
        $this->crud->column('credit_after')->label(__('admin_fields.credit_after'))->type('double');
        $this->crud->addColumn('product_id', [
            'label' => __('admin_fields.product'),
            'type' => "select",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->setColumnDetails('product_id', [
            'label' => __('admin_fields.product'),
            'type' => "select",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->addColumn('order_status_id', [
            'label' => __('admin_fields.order_status'),
            'type' => "select",
            'name' => 'order_status_id',
            'entity' => 'orderStatus',
            'attribute' => "name",
            'model' => 'App\Models\OrderStatus'
        ]);
        $this->crud->setColumnDetails('order_status_id', [
            'label' => __('admin_fields.order_status'),
            'type' => "select",
            'name' => 'order_status_id',
            'entity' => 'orderStatus',
            'attribute' => "name",
            'model' => 'App\Models\OrderStatus'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(OrderRequest::class);
        $this->crud->addField([
            'label' => __('admin_fields.order_status'),
            'type' => "relationship",
            'name' => 'order_status_id',
            'entity' => 'orderStatus',
            'attribute' => "name",
            'model' => 'App\Models\OrderStatus'
        ]);
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
            'name'           => 'orderPrepaidCardStocks',
            'label'          => 'Prepaid Card Stocks',
            'per_page'       => '10',
            'backpack_crud'  => 'order-prepaid-card-stock',
            'relation_attribute' => 'order_id',
            'button_create' => true,
            'button_delete' => true,
            'columns' => [
                [
                    'label' => 'prepaid card stock',
                    'name'  => 'prepaidCardStock.name',
                ],
                [
                    'label' => 'is printed',
                    'name'  => 'is_printed',
                ],
            ],
        ])->to('after_content');
    }
}
