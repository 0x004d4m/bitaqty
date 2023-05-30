<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreditRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class CreditCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Credit::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/credit');
        $this->crud->setEntityNameStrings(__('admin.credit'), __('admin.credits'));
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
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('amount')->label(__('admin_fields.amount'))->type('double');
        $this->crud->column('notes')->label(__('admin_fields.notes'))->type('textarea');
        $this->crud->column('deposit_or_withdraw')->label(__('admin_fields.deposit_or_withdraw'))->type('radio')
            ->options(
                [
                    0 => __('admin_fields.deposit'),
                    1 => __('admin_fields.withdraw')
                ]
            );
        $this->crud->column('credit_before')->label(__('admin_fields.credit_before'))->type('double');
        $this->crud->column('credit_after')->label(__('admin_fields.credit_after'))->type('double');
        $this->crud->addColumn('credit_type_id', [
            'label' => __('admin_fields.credit_type'),
            'type' => "select",
            'name' => 'credit_type_id',
            'entity' => 'creditType',
            'attribute' => "name",
            'model' => 'App\Models\CreditType'
        ]);
        $this->crud->setColumnDetails('credit_type_id', [
            'label' => __('admin_fields.credit_type'),
            'type' => "select",
            'name' => 'credit_type_id',
            'entity' => 'creditType',
            'attribute' => "name",
            'model' => 'App\Models\CreditType'
        ]);
        $this->crud->addColumn('credit_status_id', [
            'label' => __('admin_fields.credit_status'),
            'type' => "select",
            'name' => 'credit_status_id',
            'entity' => 'creditStatus',
            'attribute' => "name",
            'model' => 'App\Models\CreditStatus'
        ]);
        $this->crud->setColumnDetails('credit_status_id', [
            'label' => __('admin_fields.credit_status'),
            'type' => "select",
            'name' => 'credit_status_id',
            'entity' => 'creditStatus',
            'attribute' => "name",
            'model' => 'App\Models\CreditStatus'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CreditRequest::class);

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

        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('amount')->label(__('admin_fields.amount'))->type('double');
        $this->crud->field('notes')->label(__('admin_fields.notes'))->type('textarea');
        $this->crud->field('deposit_or_withdraw')
            ->label(__('admin_fields.deposit_or_withdraw'))
            ->type('radio')
            ->options([
                0 => __('admin_fields.deposit'),
                1 => __('admin_fields.withdraw')
            ]
        );
        $this->crud->field('credit_before')->label(__('admin_fields.credit_before'))->type('double');
        $this->crud->field('credit_after')->label(__('admin_fields.credit_after'))->type('double');
        $this->crud->addField([
            'label' => __('admin_fields.credit_type'),
            'type' => "relationship",
            'name' => 'credit_type_id',
            'entity' => 'creditType',
            'attribute' => "name",
            'model' => 'App\Models\CreditType'
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.credit_status'),
            'type' => "relationship",
            'name' => 'credit_status_id',
            'entity' => 'creditStatus',
            'attribute' => "name",
            'model' => 'App\Models\CreditStatus'
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
