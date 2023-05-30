<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FieldRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class FieldCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Field::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/field');
        $this->crud->setEntityNameStrings(__('admin.field'), __('admin.fields'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->addColumn('field_type_id', [
            'label' => __('admin_fields.field_type'),
            'type' => "select",
            'name' => 'field_type_id',
            'entity' => 'fieldType',
            'attribute' => "name",
            'model' => 'App\Models\FieldType'
        ]);
        $this->crud->setColumnDetails('field_type_id', [
            'label' => __('admin_fields.field_type'),
            'type' => "select",
            'name' => 'field_type_id',
            'entity' => 'fieldType',
            'attribute' => "name",
            'model' => 'App\Models\FieldType'
        ]);
        $this->crud->column('is_confirmed')->label(__('admin_fields.is_confirmed'))->type('boolean');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(FieldRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->addField([
            'label' => __('admin_fields.field_type'),
            'type' => "relationship",
            'name' => 'field_type_id',
            'entity' => 'fieldType',
            'attribute' => "name",
            'model' => 'App\Models\FieldType'
        ]);
        $this->crud->field('is_confirmed')->label(__('admin_fields.is_confirmed'))->type('boolean');
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
