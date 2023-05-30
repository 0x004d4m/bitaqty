<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreditCardRequest;
use App\Models\CreditCard;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class CreditCardCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\CreditCard::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/credit-card');
        $this->crud->setEntityNameStrings(__('admin.credit_card'), __('admin.credit_cards'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('number')->label(__('admin_fields.number'))->type('text');
        $this->crud->column('value')->label(__('admin_fields.value'))->type('double');
        $this->crud->column('qr')->label(__('admin_fields.qr'))->type('text');
        $this->crud->addColumn('credit_id', [
            'label' => __('admin_fields.credit'),
            'type' => "select",
            'name' => 'credit_id',
            'entity' => 'credit',
            'attribute' => "name",
            'model' => 'App\Models\Credit'
        ]);
        $this->crud->setColumnDetails('credit_id', [
            'label' => __('admin_fields.credit'),
            'type' => "select",
            'name' => 'credit_id',
            'entity' => 'credit',
            'attribute' => "name",
            'model' => 'App\Models\Credit'
        ]);
        $this->crud->addColumn('supported_account_id', [
            'label' => __('admin_fields.supported_account'),
            'type' => "select",
            'name' => 'supported_account_id',
            'entity' => 'supportedAccount',
            'attribute' => "name",
            'model' => 'App\Models\SupportedAccount'
        ]);
        $this->crud->setColumnDetails('supported_account_id', [
            'label' => __('admin_fields.supported_account'),
            'type' => "select",
            'name' => 'supported_account_id',
            'entity' => 'supportedAccount',
            'attribute' => "name",
            'model' => 'App\Models\SupportedAccount'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CreditCardRequest::class);

        $this->crud->field('number')->label(__('admin_fields.number'))->type('text')->default($this->generateNewNumber())->attributes([
            "readonly"=>true
        ]);
        $this->crud->field('value')->label(__('admin_fields.value'))->type('double');
        $this->crud->field('qr')->label(__('admin_fields.qr'))->type('text');
        $this->crud->addField([
            'label' => __('admin_fields.credit'),
            'type' => "relationship",
            'name' => 'credit_id',
            'entity' => 'credit',
            'attribute' => "name",
            'model' => 'App\Models\Credit'
        ]);
        $this->crud->addField([
            'label' => __('admin_fields.supported_account'),
            'type' => "relationship",
            'name' => 'supported_account_id',
            'entity' => 'supportedAccount',
            'attribute' => "name",
            'model' => 'App\Models\SupportedAccount'
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

    protected function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function generateNewNumber()
    {
        while(true){
            $number=$this->generateRandomString();
            if(CreditCard::where('number',$number)->count()==0){
                return $number;
            }
        }
    }
}
