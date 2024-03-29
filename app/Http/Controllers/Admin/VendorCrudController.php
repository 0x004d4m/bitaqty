<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VendorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class VendorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Vendor::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/vendor');
        $this->crud->setEntityNameStrings(__('admin.vendor'), __('admin.vendors'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('address')->label(__('admin_fields.address'))->type('textarea');
        $this->crud->column('phone')->label(__('admin_fields.phone'))->type('text');
        $this->crud->column('password')->label(__('admin_fields.password'))->type('text');
        $this->crud->column('credit')->label(__('admin_fields.credit'))->type('double');
        $this->crud->column('dept')->label(__('admin_fields.dept'))->type('double');
        $this->crud->column('email')->label(__('admin_fields.email'))->type('email');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('is_blocked')->label(__('admin_fields.is_blocked'))->type('boolean');
        $this->crud->addColumn('country_id', [
            'label' => __('admin_fields.country'),
            'type' => "select",
            'name' => 'country_id',
            'entity' => 'country',
            'attribute' => "name",
            'model' => 'App\Models\Country'
        ]);
        $this->crud->setColumnDetails('country_id', [
            'label' => __('admin_fields.country'),
            'type' => "select",
            'name' => 'country_id',
            'entity' => 'country',
            'attribute' => "name",
            'model' => 'App\Models\Country'
        ]);
        $this->crud->addColumn('state_id', [
            'label' => __('admin_fields.state'),
            'type' => "select",
            'name' => 'state_id',
            'entity' => 'state',
            'attribute' => "name",
            'model' => 'App\Models\State'
        ]);
        $this->crud->setColumnDetails('state_id', [
            'label' => __('admin_fields.state'),
            'type' => "select",
            'name' => 'state_id',
            'entity' => 'state',
            'attribute' => "name",
            'model' => 'App\Models\State'
        ]);
        $this->crud->addColumn('currency_id', [
            'label' => __('admin_fields.currency'),
            'type' => "select",
            'name' => 'currency_id',
            'entity' => 'currency',
            'attribute' => "name",
            'model' => 'App\Models\Currency'
        ]);
        $this->crud->setColumnDetails('currency_id', [
            'label' => __('admin_fields.currency'),
            'type' => "select",
            'name' => 'currency_id',
            'entity' => 'currency',
            'attribute' => "name",
            'model' => 'App\Models\Currency'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(VendorRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.country'),
            'type' => "relationship",
            'name' => 'country_id',
            'entity' => 'country',
            'attribute' => "name",
            'model' => 'App\Models\Country'
        ]);
        $this->crud->addField([
            'label'                => __('admin_fields.state'),
            'type'                 => 'select2_from_ajax',
            'name'                 => 'state_id',
            'entity'               => 'state',
            'attribute'            => 'name',
            'data_source'          => url('admin/States'),
            'placeholder'          => 'Select a state',
            'include_all_form_fields' => true,
            'minimum_input_length' => 0,
            'dependencies'         => ['country_id'],
            'method'               => 'GET',
        ]);
        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('address')->label(__('admin_fields.address'))->type('textarea');
        $this->crud->field('phone')->label(__('admin_fields.phone'))->type('text');
        $this->crud->field('password')->label(__('admin_fields.password'))->type('password');
        $this->crud->field('credit')->label(__('admin_fields.credit'))->type('double');
        $this->crud->field('dept')->label(__('admin_fields.dept'))->type('double');
        $this->crud->field('email')->label(__('admin_fields.email'))->type('email');
        $this->crud->field('is_blocked')->label(__('admin_fields.is_blocked'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.currency'),
            'type' => "relationship",
            'name' => 'currency_id',
            'entity' => 'currency',
            'attribute' => "name",
            'model' => 'App\Models\Currency'
        ]);
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(VendorRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.country'),
            'type' => "relationship",
            'name' => 'country_id',
            'entity' => 'country',
            'attribute' => "name",
            'model' => 'App\Models\Country'
        ]);
        $this->crud->addField([
            'label'                => __('admin_fields.state'),
            'type'                 => 'select2_from_ajax',
            'name'                 => 'state_id',
            'entity'               => 'state',
            'attribute'            => 'name',
            'data_source'          => url('admin/States'),
            'placeholder'          => 'Select a state',
            'include_all_form_fields' => true,
            'minimum_input_length' => 0,
            'dependencies'         => ['country_id'],
            'method'               => 'GET',
        ]);
        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('address')->label(__('admin_fields.address'))->type('textarea');
        $this->crud->field('phone')->label(__('admin_fields.phone'))->type('text');
        $this->crud->field('email')->label(__('admin_fields.email'))->type('email');
        $this->crud->field('is_blocked')->label(__('admin_fields.is_blocked'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.currency'),
            'type' => "relationship",
            'name' => 'currency_id',
            'entity' => 'currency',
            'attribute' => "name",
            'model' => 'App\Models\Currency'
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
