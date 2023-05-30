<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DashboardValueRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class DashboardValueCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\DashboardValue::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/dashboard-value');
        $this->crud->setEntityNameStrings(__('admin.dashboard_value'), __('admin.dashboard_values'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('is_visible')->label(__('admin_fields.is_visible'))->type('boolean');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DashboardValueRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('is_visible')->label(__('admin_fields.is_visible'))->type('boolean');
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
