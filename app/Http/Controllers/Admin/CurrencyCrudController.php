<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

class CurrencyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Currency::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/currency');
        $this->crud->setEntityNameStrings(__('admin.currency'), __('admin.currencies'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('symbol')->label(__('admin_fields.symbol'))->type('text');
        $this->crud->column('to_usd')->label(__('admin_fields.to_usd'))->type('double');
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
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CurrencyRequest::class);

        $country_id = null;
        if (isset($_GET['country_id'])) {
            $country_id = $_GET['country_id'];
        } else {
            $Currency = Currency::where('id', Route::current()->parameter('id'))->first();
            if ($Currency) {
                $country_id = $Currency->country_id;
            }
        }
        $this->crud->addField([
            'type' => "hidden",
            'name' => 'country_id',
            'default' => $country_id
        ]);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('symbol')->label(__('admin_fields.symbol'))->type('text');
        $this->crud->field('to_usd')->label(__('admin_fields.to_usd'))->type('double');
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
