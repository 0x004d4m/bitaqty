<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductFieldRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ProductFieldCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\ProductField::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/product-field');
        $this->crud->setEntityNameStrings(__('admin.product_field'), __('admin.product_fields'));
    }

    protected function setupListOperation()
    {
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
        $this->crud->addColumn('field_id', [
            'label' => __('admin_fields.field'),
            'type' => "select",
            'name' => 'field_id',
            'entity' => 'field',
            'attribute' => "name",
            'model' => 'App\Models\Field'
        ]);
        $this->crud->setColumnDetails('field_id', [
            'label' => __('admin_fields.field'),
            'type' => "select",
            'name' => 'field_id',
            'entity' => 'field',
            'attribute' => "name",
            'model' => 'App\Models\Field'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ProductFieldRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.product'),
            'type' => "relationship",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.field'),
            'type' => "relationship",
            'name' => 'field_id',
            'entity' => 'field',
            'attribute' => "name",
            'model' => 'App\Models\Field'
        ]);
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
