<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubcategoryRequest;
use App\Models\Subcategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Request;

class SubcategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Subcategory::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/subcategory');
        $this->crud->setEntityNameStrings(__('admin.subcategory'), __('admin.subcategories'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('is_active')->label(__('admin_fields.is_active'))->type('boolean');
        $this->crud->column('has_limit')->label(__('admin_fields.has_limit'))->type('boolean');
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

        $this->crud->addFilter(
            [
                'type'  => 'text',
                'name'  => 'category_id',
                'label' => __('admin_fields.category')
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereHas', 'category', function($q) use($value){
                    $q->where('name', 'LIKE', '%'.$value.'%');
                });
            }
        );
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SubcategoryRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('is_active')->label(__('admin_fields.is_active'))->type('boolean');
        $this->crud->addField([
            'label' => __('admin_fields.category'),
            'type' => "relationship",
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => "name",
            'model' => 'App\Models\Category'
        ]);

        $this->crud->addField([
            'label' => __('admin_fields.fields'),
            'type' => 'select_multiple',
            'name' => 'fields', // the method that defines the relationship in your Model
            'entity' => 'fields', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Field", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->field('has_limit')->label(__('admin_fields.has_limit'))->type('boolean')->default(true);

        $this->crud->addField([
            'label' => __('admin_fields.clients'),
            'type' => 'select_multiple',
            'name' => 'clients', // the method that defines the relationship in your Model
            'entity' => 'clients', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Client", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->addField([
            'label' => __('admin_fields.vendors'),
            'type' => 'select_multiple',
            'name' => 'vendors', // the method that defines the relationship in your Model
            'entity' => 'vendors', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Vendor", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        Widget::add()->type('script')->content('toggle.js');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function subcategories(Request $request)
    {

        // NOTE: this is a Backpack helper that parses your form input into an usable array.
        // you still have the original request as `request('form')`
        $form = backpack_form_input();

        $options = Subcategory::query();

        if (isset($form['category_id'])) {
            $options = $options->where('category_id', $form['category_id']);
        }

        $results = $options->paginate(100);

        return $results;
    }
}
