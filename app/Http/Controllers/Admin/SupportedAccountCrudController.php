<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SupportedAccountRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class SupportedAccountCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\SupportedAccount::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/supported-account');
        $this->crud->setEntityNameStrings(__('admin.supported_account'), __('admin.supported_accounts'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('is_active')->label(__('admin_fields.is_active'))->type('boolean');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SupportedAccountRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('is_active')->label(__('admin_fields.is_active'))->type('boolean');
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
