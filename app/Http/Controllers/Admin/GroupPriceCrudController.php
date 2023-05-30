<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GroupPriceRequest;
use App\Models\GroupPrice;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

class GroupPriceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\GroupPrice::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-price');
        $this->crud->setEntityNameStrings(__('admin.group_price'), __('admin.group_prices'));
    }

    protected function setupListOperation()
    {
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
        $this->crud->addColumn('product_id', [
            'label' => __('admin_fields.product'),
            'type' => "select",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->setColumnDetails('product_id', [
            'label' => __('admin_fields.product'),
            'type' => "select",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->column('price')->label(__('admin_fields.price'))->type('double');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(GroupPriceRequest::class);
        $this->crud->removeSaveAction('save_and_preview');
        $this->crud->removeSaveAction('save_and_edit');
        $this->crud->removeSaveAction('save_and_new');

        $group_id = null;
        if (isset($_GET['group_id'])) {
            $group_id = $_GET['group_id'];
        } else {
            $GroupPrice = GroupPrice::where('id', Route::current()->parameter('id'))->first();
            if ($GroupPrice) {
                $group_id = $GroupPrice->group_id;
            }
        }
        $this->crud->addField([
            'type' => "hidden",
            'name' => 'group_id',
            'default' => $group_id
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.product'),
            'type' => "relationship",
            'name' => 'product_id',
            'entity' => 'product',
            'attribute' => "name",
            'model' => 'App\Models\Product'
        ]);
        $this->crud->field('price')->label(__('admin_fields.price'))->type('double');
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
