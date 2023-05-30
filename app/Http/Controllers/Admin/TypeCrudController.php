<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class TypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Type::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/type');
        $this->crud->setEntityNameStrings(__('admin.type'), __('admin.types'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('need_approval')->label(__('admin_fields.need_approval'))->type('boolean');
        $this->crud->column('is_active')->label(__('admin_fields.is_active'))->type('boolean');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(TypeRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('need_approval')->label(__('admin_fields.need_approval'))->type('boolean');
        $this->crud->field('is_active')->label(__('admin_fields.is_active'))->type('boolean');
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
