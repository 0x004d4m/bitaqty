<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Vendor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class ClientCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Client::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/client');
        $this->crud->setEntityNameStrings(__('admin.client'), __('admin.clients'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('address')->label(__('admin_fields.address'))->type('textarea');
        $this->crud->column('phone')->label(__('admin_fields.phone'))->type('text');
        $this->crud->column('password')->label(__('admin_fields.password'))->type('text');
        $this->crud->column('commercial_name')->label(__('admin_fields.commercial_name'))->type('text');
        $this->crud->column('credit')->label(__('admin_fields.credit'))->type('double');
        $this->crud->column('email')->label(__('admin_fields.email'))->type('email');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('is_approved')->label(__('admin_fields.is_approved'))->type('boolean');
        $this->crud->column('is_blocked')->label(__('admin_fields.is_blocked'))->type('boolean');
        $this->crud->column('can_give_credit')->label(__('admin_fields.can_give_credit'))->type('boolean');
        $this->crud->addColumn('group_id', [
            'label' => __('admin_fields.group'),
            'type' => "select",
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => "name",
            'model' => 'App\Models\Group'
        ]);
        $this->crud->setColumnDetails('group_id', [
            'label' => __('admin_fields.group'),
            'type' => "select",
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => "name",
            'model' => 'App\Models\Group'
        ]);
        $this->crud->addColumn('vendor_id', [
            'label' => __('admin_fields.vendor'),
            'type' => "select",
            'name' => 'vendor_id',
            'entity' => 'vendor',
            'attribute' => "name",
            'model' => 'App\Models\Vendor'
        ]);
        $this->crud->setColumnDetails('vendor_id', [
            'label' => __('admin_fields.vendor'),
            'type' => "select",
            'name' => 'vendor_id',
            'entity' => 'vendor',
            'attribute' => "name",
            'model' => 'App\Models\Vendor'
        ]);
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
        $this->crud->setValidation(ClientRequest::class);

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
        $this->crud->field('commercial_name')->label(__('admin_fields.commercial_name'))->type('text');
        $this->crud->field('credit')->label(__('admin_fields.credit'))->type('double');
        $this->crud->field('email')->label(__('admin_fields.email'))->type('email');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('is_approved')->label(__('admin_fields.is_approved'))->type('boolean');
        $this->crud->field('is_blocked')->label(__('admin_fields.is_blocked'))->type('boolean');
        $this->crud->field('can_give_credit')->label(__('admin_fields.can_give_credit'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.group'),
            'type' => "relationship",
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => "name",
            'model' => 'App\Models\Group'
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.vendor'),
            'type' => "relationship",
            'name' => 'vendor_id',
            'entity' => 'vendor',
            'attribute' => "name",
            'model' => 'App\Models\Vendor'
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.currency'),
            'type' => "relationship",
            'name' => 'currency_id',
            'entity' => 'currency',
            'attribute' => "name",
            'model' => 'App\Models\Currency'
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateClientRequest::class);

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
        $this->crud->field('commercial_name')->label(__('admin_fields.commercial_name'))->type('text');
        $this->crud->field('email')->label(__('admin_fields.email'))->type('email');
        $this->crud->field('is_approved')->label(__('admin_fields.is_approved'))->type('boolean');
        $this->crud->field('is_blocked')->label(__('admin_fields.is_blocked'))->type('boolean');
        $this->crud->field('can_give_credit')->label(__('admin_fields.can_give_credit'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.group'),
            'type' => "relationship",
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => "name",
            'model' => 'App\Models\Group'
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.vendor'),
            'type' => "relationship",
            'name' => 'vendor_id',
            'entity' => 'vendor',
            'attribute' => "name",
            'model' => 'App\Models\Vendor'
        ]);
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

    public function users(Request $request)
    {
        $form = backpack_form_input();

        $options = [];
        if (isset($form['userable_type']) && $form['userable_type']== Client::class) {
            $options = Client::paginate(10000);
        }
        if (isset($form['userable_type']) && $form['userable_type']== Vendor::class) {
            $options = Vendor::paginate(10000);
        }

        $results = $options;

        return $results;
    }
}
