<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FieldTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class FieldTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\FieldType::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/field-type');
        $this->crud->setEntityNameStrings(__('admin.field_type'), __('admin.field_types'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(FieldTypeRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
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
