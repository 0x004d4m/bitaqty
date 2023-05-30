<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MaintenanceRequest;
use App\Models\Maintenance;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class MaintenanceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Maintenance::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/maintenance');
        $this->crud->setEntityNameStrings(__('admin.maintenance'), __('admin.maintenances'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('type')->label(__('admin_fields.type'))->type('text');
        $this->crud->column('is_active')->label(__('admin_fields.is_active'))->type('boolean');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MaintenanceRequest::class);

        $this->crud->field('type')->label(__('admin_fields.type'))->type('text');
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

    public function on()
    {
        $Maintenance = Maintenance::first();
        $Maintenance->update([
            "is_active"=>1
        ]);
        \Alert::success(__('admin.maintenance_on'))->flash();
        return back();
    }

    public function off()
    {
        $Maintenance = Maintenance::first();
        $Maintenance->update([
            "is_active" => 0
        ]);
        \Alert::success(__('admin.maintenance_off'))->flash();
        return back();
    }
}
