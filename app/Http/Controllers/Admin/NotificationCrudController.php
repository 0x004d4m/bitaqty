<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NotificationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class NotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Notification::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/notification');
        $this->crud->setEntityNameStrings(__('admin.notification'), __('admin.notifications'));
    }

    protected function setupListOperation()
    {
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
        $this->crud->column('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->column('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(NotificationRequest::class);

        $this->crud->addField([   // select_from_array
            'name'        => 'userable_type',
            'label'       => __('admin_fields.userable_type'),
            'type'        => 'select_from_array',
            'options'     => ['App\Models\Client' => 'Client', 'App\Models\Vendor' => 'Vendor'],
            'allows_null' => false,
        ]);
        $this->crud->addField([
            'label'                => __('admin_fields.user'),
            'type'                 => 'select2_from_ajax',
            'name'                 => 'userable',
            'entity'               => 'userable',
            'attribute'            => 'name',
            'data_source'          => url('admin/Users'),
            'placeholder'          => 'Select a User',
            'include_all_form_fields' => true,
            'minimum_input_length' => 0,
            'dependencies'         => ['userable_type'],
            'method'               => 'GET',
        ]);
        $this->crud->field('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
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
