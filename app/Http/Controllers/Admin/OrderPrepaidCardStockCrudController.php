<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderPrepaidCardStockRequest;
use App\Models\OrderPrepaidCardStock;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

class OrderPrepaidCardStockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\OrderPrepaidCardStock::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/order-prepaid-card-stock');
        $this->crud->setEntityNameStrings(__('admin.order_prepaid_card_stock'), __('admin.order_prepaid_card_stocks'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('is_printed')->label(__('admin_fields.is_printed'))->type('boolean');
        $this->crud->addColumn('order_id', [
            'label' => __('admin_fields.order'),
            'type' => "select",
            'name' => 'order_id',
            'entity' => 'order',
            'attribute' => "name",
            'model' => 'App\Models\Order'
        ]);
        $this->crud->setColumnDetails('order_id', [
            'label' => __('admin_fields.order'),
            'type' => "select",
            'name' => 'order_id',
            'entity' => 'order',
            'attribute' => "id",
            'model' => 'App\Models\Order'
        ]);
        $this->crud->addColumn('prepaid_card_stock_id', [
            'label' => __('admin_fields.prepaid_card_stock'),
            'type' => "select",
            'name' => 'prepaid_card_stock_id',
            'entity' => 'prepaidCardStock',
            'attribute' => "id",
            'model' => 'App\Models\PrepaidCardStock'
        ]);
        $this->crud->setColumnDetails('prepaid_card_stock_id', [
            'label' => __('admin_fields.prepaid_card_stock'),
            'type' => "select",
            'name' => 'prepaid_card_stock_id',
            'entity' => 'prepaidCardStock',
            'attribute' => "name",
            'model' => 'App\Models\PrepaidCardStock'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(OrderPrepaidCardStockRequest::class);
        $this->crud->removeSaveAction('save_and_preview');
        $this->crud->removeSaveAction('save_and_edit');
        $this->crud->removeSaveAction('save_and_new');

        $order_id = null;
        if (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            $State = OrderPrepaidCardStock::where('id', Route::current()->parameter('id'))->first();
            if ($State) {
                $order_id = $State->order_id;
            }
        }
        $this->crud->addField([
            'type' => "hidden",
            'name' => 'order_id',
            'default' => $order_id
        ]);

        $this->crud->field('is_printed')->label(__('admin_fields.is_printed'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.prepaid_card_stock'),
            'type' => "relationship",
            'name' => 'prepaid_card_stock_id',
            'entity' => 'prepaidCardStock',
            'attribute' => "name",
            'model' => 'App\Models\PrepaidCardStock'
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
