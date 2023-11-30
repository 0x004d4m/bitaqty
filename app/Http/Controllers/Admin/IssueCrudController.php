<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IssueRequest;
use App\Models\Client;
use App\Models\Vendor;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class IssueCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Issue::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/issue');
        $this->crud->setEntityNameStrings(__('admin.issue'), __('admin.issues'));
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
        $this->crud->column('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('solution')->label(__('admin_fields.solution'))->type('textarea');
        $this->crud->column('is_solved')->label(__('admin_fields.is_solved'))->type('boolean');
        $this->crud->column('is_duplicate')->label(__('admin_fields.is_duplicate'))->type('boolean');
        $this->crud->addColumn('issue_type_id', [
            'label' => __('admin_fields.issue_type'),
            'type' => "select",
            'name' => 'issue_type_id',
            'entity' => 'issueType',
            'attribute' => "name",
            'model' => 'App\Models\IssueType'
        ]);
        $this->crud->setColumnDetails('issue_type_id', [
            'label' => __('admin_fields.issue_type'),
            'type' => "select",
            'name' => 'issue_type_id',
            'entity' => 'issueType',
            'attribute' => "name",
            'model' => 'App\Models\IssueType'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(IssueRequest::class);

        $this->crud->addField([   // select_from_array
            'name'        => 'userable_type',
            'label'       => __('admin_fields.userable_type'),
            'type'        => 'select_from_array',
            'options'     => [Client::class => 'Client', Vendor::class => 'Vendor'],
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
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('solution')->label(__('admin_fields.solution'))->type('textarea');
        $this->crud->field('is_solved')->label(__('admin_fields.is_solved'))->type('boolean');
        $this->crud->field('is_duplicate')->label(__('admin_fields.is_duplicate'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.issue_type'),
            'type' => "relationship",
            'name' => 'issue_type_id',
            'entity' => 'issueType',
            'attribute' => "name",
            'model' => 'App\Models\IssueType'
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
