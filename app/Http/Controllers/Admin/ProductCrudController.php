<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Product::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/product');
        $this->crud->setEntityNameStrings(__('admin.product'), __('admin.products'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->column('unavailable_notes')->label(__('admin_fields.unavailable_notes'))->type('textarea');
        $this->crud->column('how_to_use')->label(__('admin_fields.how_to_use'))->type('textarea');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('price')->label(__('admin_fields.price'))->type('double');
        $this->crud->column('suggested_price')->label(__('admin_fields.suggested_price'))->type('double');
        $this->crud->column('cost_price')->label(__('admin_fields.cost_price'))->type('double');
        $this->crud->column('selling_price')->label(__('admin_fields.selling_price'))->type('double');
        $this->crud->column('stock')->label(__('admin_fields.stock'))->type('number');
        $this->crud->column('stock_limit')->label(__('admin_fields.stock_limit'))->type('number');
        $this->crud->column('is_active')->label(__('admin_fields.is_active'))->type('boolean');
        $this->crud->column('is_vip')->label(__('admin_fields.is_vip'))->type('boolean');
        $this->crud->addColumn('type_id', [
            'label' => __('admin_fields.type'),
            'type' => "select",
            'name' => 'type_id',
            'entity' => 'type',
            'attribute' => "name",
            'model' => 'App\Models\Type'
        ]);
        $this->crud->setColumnDetails('type_id', [
            'label' => __('admin_fields.type'),
            'type' => "select",
            'name' => 'type_id',
            'entity' => 'type',
            'attribute' => "name",
            'model' => 'App\Models\Type'
        ]);
        $this->crud->addColumn('category_id', [
            'label' => __('admin_fields.category'),
            'type' => "select",
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => "name",
            'model' => 'App\Models\Category'
        ]);
        $this->crud->setColumnDetails('category_id', [
            'label' => __('admin_fields.category'),
            'type' => "select",
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => "name",
            'model' => 'App\Models\Category'
        ]);
        $this->crud->addColumn('subcategory_id', [
            'label' => __('admin_fields.subcategory'),
            'type' => "select",
            'name' => 'subcategory_id',
            'entity' => 'subcategory',
            'attribute' => "name",
            'model' => 'App\Models\Subcategory'
        ]);
        $this->crud->setColumnDetails('subcategory_id', [
            'label' => __('admin_fields.subcategory'),
            'type' => "select",
            'name' => 'subcategory_id',
            'entity' => 'subcategory',
            'attribute' => "name",
            'model' => 'App\Models\Subcategory'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ProductRequest::class);

        $this->crud->addField([
            'label' => __('admin_fields.type'),
            'type' => "relationship",
            'name' => 'type_id',
            'entity' => 'type',
            'attribute' => "name",
            'model' => 'App\Models\Type'
        ]);

        $this->crud->addField([
            'label'                => __('admin_fields.category'),
            'type'                 => 'select2_from_ajax',
            'name'                 => 'category_id',
            'entity'               => 'category',
            'attribute'            => 'name["ar"]',
            'data_source'          => url('admin/Categories'),
            'placeholder'          => 'Select a Category',
            'include_all_form_fields' => true,
            'minimum_input_length' => 0,
            'dependencies'         => ['type_id'],
            'method'               => 'GET',
        ]);

        $this->crud->addField([
            'label'                => __('admin_fields.subcategory'),
            'type'                 => 'select2_from_ajax',
            'name'                 => 'subcategory_id',
            'entity'               => 'subcategory',
            'attribute'            => 'name',
            'data_source'          => url('admin/Subcategories'),
            'placeholder'          => 'Select a Subcategory',
            'include_all_form_fields' => true,
            'minimum_input_length' => 0,
            'dependencies'         => ['category_id'],
            'method'               => 'GET',
        ]);
        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->field('unavailable_notes')->label(__('admin_fields.unavailable_notes'))->type('textarea');
        $this->crud->field('how_to_use')->label(__('admin_fields.how_to_use'))->type('textarea');
        $this->crud->field('price')->label(__('admin_fields.price'))->type('double');
        $this->crud->field('suggested_price')->label(__('admin_fields.suggested_price'))->type('double');
        $this->crud->field('cost_price')->label(__('admin_fields.cost_price'))->type('double');
        $this->crud->field('selling_price')->label(__('admin_fields.selling_price'))->type('double');
        $this->crud->field('stock')->label(__('admin_fields.stock'))->type('number');
        $this->crud->field('stock_limit')->label(__('admin_fields.stock_limit'))->type('number');
        $this->crud->field('is_active')->label(__('admin_fields.is_active'))->type('boolean');
        $this->crud->field('is_vip')->label(__('admin_fields.is_vip'))->type('boolean');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
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
