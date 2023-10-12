<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PrepaidCardStockImportRequest;
use App\Http\Requests\PrepaidCardStockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class PrepaidCardStockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\ImportOperation;

    public function setup()
    {
        $this->crud->allowAccess('import');
        $this->crud->setModel(\App\Models\PrepaidCardStock::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/prepaid-card-stock');
        $this->crud->setEntityNameStrings(__('admin.prepaid_card_stock'), __('admin.prepaid_card_stocks'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('serial1')->label(__('admin_fields.serial1'))->type('text');
        $this->crud->column('serial2')->label(__('admin_fields.serial2'))->type('text');
        $this->crud->column('number1')->label(__('admin_fields.number1'))->type('text');
        $this->crud->column('number2')->label(__('admin_fields.number2'))->type('text');
        $this->crud->column('cvc')->label(__('admin_fields.cvc'))->type('text');
        $this->crud->column('expiration_date')->label(__('admin_fields.expiration_date'))->type('date');
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
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PrepaidCardStockRequest::class);

        $this->crud->field('serial1')->label(__('admin_fields.serial1'))->type('text');
        $this->crud->field('serial2')->label(__('admin_fields.serial2'))->type('text');
        $this->crud->field('number1')->label(__('admin_fields.number1'))->type('text');
        $this->crud->field('number2')->label(__('admin_fields.number2'))->type('text');
        $this->crud->field('cvc')->label(__('admin_fields.cvc'))->type('text');
        $this->crud->field('expiration_date')->label(__('admin_fields.expiration_date'))->type('date');
        $this->crud->addField([
            'label' => __('admin_fields.product'),
            'type' => "relationship",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
    }

    protected function setupImportOperation()
    {
        // $this->crud->setValidation(PrepaidCardStockImportRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.product'),
            'type' => "relationship",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->field('excel')->label(__('admin_fields.excel'))->type('upload');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
