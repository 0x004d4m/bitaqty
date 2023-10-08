<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Category::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/category');
        $this->crud->setEntityNameStrings(__('admin.category'), __('admin.categories'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->column('order')->label(__('admin_fields.order'))->type('number');
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
        $this->crud->column('is_active')->label(__('admin_fields.is_active'))->type('boolean');

        $this->crud->addFilter(
            [
                'type'  => 'text',
                'name'  => 'type_id',
                'label' => __('admin_fields.type')
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereHas', 'type', function ($q) use ($value) {
                    $q->where('name', 'LIKE', '%' . $value . '%');
                });
            }
        );
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CategoryRequest::class);

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
        $this->crud->field('order')->label(__('admin_fields.order'))->type('number');
        $this->crud->addField([
            'label' => __('admin_fields.type'),
            'type' => "relationship",
            'name' => 'type_id',
            'entity' => 'type',
            'attribute' => "name",
            'model' => 'App\Models\Type'
        ]);
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->field('is_active')->label(__('admin_fields.is_active'))->type('boolean');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function categories(Request $request)
    {
        $form = backpack_form_input();

        $options = Category::query();
        if (isset($form['type_id'])) {
            $options = $options->where('type_id', $form['type_id']);
        }

        if($request->has('term')){
            $options = $options->where('name', 'like', '%' . $request->input('term') . '%');
        }

        $results = $options->paginate(100);
        return $results;
    }
}
