<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VendorProfitRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class VendorProfitCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\VendorProfit::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/vendor-profit');
        $this->crud->setEntityNameStrings(__('admin.vendor_profit'), __('admin.vendor_profits'));
    }

    protected function setupListOperation()
    {
        $this->crud->addField([
            'label' => __('admin_fields.vendor'),
            'type' => "relationship",
            'name' => 'vendor_id',
            'entity' => 'vendor',
            'attribute' => "name",
            'model' => 'App\Models\Vendor'
        ]);
        $this->crud->column('notes')->label(__('admin_fields.amount'))->type('text');
        $this->crud->column('amount')->label(__('admin_fields.notes'))->type('textarea');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(VendorProfitRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.vendor'),
            'type' => "relationship",
            'name' => 'vendor_id',
            'entity' => 'vendor',
            'attribute' => "name",
            'model' => 'App\Models\Vendor'
        ]);
        $this->crud->field('amount')->label(__('admin_fields.amount'))->type('text');
        $this->crud->field('notes')->label(__('admin_fields.notes'))->type('textarea');
    }
}
