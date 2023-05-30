<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IssueTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class IssueTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\IssueType::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/issue-type');
        $this->crud->setEntityNameStrings(__('admin.issue_type'), __('admin.issue_types'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(IssueTypeRequest::class);

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
