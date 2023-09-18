<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NewsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class NewsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\News::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/news');
        $this->crud->setEntityNameStrings(__('admin.new'), __('admin.news'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('type')->label(__('admin_fields.type'))->type('type');
        $this->crud->column('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->column('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->column('action')->label(__('admin_fields.action'))->type('text');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(NewsRequest::class);

        $this->crud->addField([   // select_from_array
            'name'        => 'type',
            'label'       => __('admin_fields.type'),
            'type'        => 'select_from_array',
            'options'     => ['client' => 'client', 'vendor' => 'vendor'],
            'allows_null' => false,
        ]);
        $this->crud->field('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->field('action')->label(__('admin_fields.action'))->type('text');
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
