<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CountryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\Widget;

class CountryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Country::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/country');
        $this->crud->setEntityNameStrings(__('admin.country'), __('admin.countries'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('code')->label(__('admin_fields.code'))->type('text');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CountryRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('code')->label(__('admin_fields.code'))->type('text');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        Widget::add([
            'type'           => 'relation_table',
            'name'           => 'states',
            'label'          => __('admin.states'),
            'per_page'       => '10',
            'backpack_crud'  => 'state',
            'relation_attribute' => 'country_id',
            'button_create' => true,
            'button_delete' => true,
            'columns' => [
                [
                    'label' => __('admin_fields.name'),
                    'name'  => 'name',
                ],
            ],
        ])->to('after_content');

        Widget::add([
            'type'           => 'relation_table',
            'name'           => 'currencies',
            'label'          => __('admin.currencies'),
            'per_page'       => '10',
            'backpack_crud'  => 'currency',
            'relation_attribute' => 'country_id',
            'button_create' => true,
            'button_delete' => true,
            'columns' => [
                [
                    'label' => __('admin_fields.name'),
                    'name'  => 'name',
                ],
                [
                    'label' => __('admin_fields.symbol'),
                    'name'  => 'symbol',
                ],
                [
                    'label' => __('admin_fields.to_jod'),
                    'name'  => 'to_jod',
                ],
            ],
        ])->to('after_content');
    }
}
