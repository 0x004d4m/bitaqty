<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InAppMessageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class InAppMessageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\InAppMessage::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/in-app-message');
        $this->crud->setEntityNameStrings(__('in app message'), __('in app messages'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('type')->label(__('admin_fields.type'))->type('text');
        $this->crud->column('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->column('description')->label(__('admin_fields.description'))->type('text');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('action')->label(__('admin_fields.action'))->text('text');
        $this->crud->column('is_important')->label(__('admin_fields.is_important'))->type('boolean');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(InAppMessageRequest::class);

        $this->crud->addField([   // select_from_array
            'name'        => 'type',
            'label'       => __('admin_fields.type'),
            'type'        => 'select_from_array',
            'options'     => ['client' => 'client', 'vendor' => 'vendor'],
            'allows_null' => false,
        ]);
        $this->crud->field('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('action')->label(__('admin_fields.action'))->type('text');
        $this->crud->field('is_important')->label(__('admin_fields.is_important'))->type('boolean');
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
