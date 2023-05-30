<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubcategoryFieldRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class SubcategoryFieldCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\SubcategoryField::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/subcategory-field');
        $this->crud->setEntityNameStrings(__('admin.subcategory_field'), __('admin.subcategory_fields'));
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn('subcategory_id', [
            'label' => __('admin_fields.subcategory'),
            'type' => "select",
            'name' => 'subcategory_id',
            'entity' => 'subcategory',
            'attribute' => "name",
            'model' => 'App\Models\Subcategory'
        ]);
        $this->crud->setColumnDetails('subcategory_id', [
            'label' => __('admin_fields.subcategory'),
            'type' => "select",
            'name' => 'subcategory_id',
            'entity' => 'subcategory',
            'attribute' => "name",
            'model' => 'App\Models\Subcategory'
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
        $this->crud->setValidation(SubcategoryFieldRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.subcategory'),
            'type' => "relationship",
            'name' => 'subcategory_id',
            'entity' => 'subcategory',
            'attribute' => "name",
            'model' => 'App\Models\Subcategory'
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
