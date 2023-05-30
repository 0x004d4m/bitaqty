<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StateRequest;
use App\Models\Country;
use App\Models\State;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class StateCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\State::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/state');
        $this->crud->setEntityNameStrings(__('admin.state'), __('admin.states'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
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
        $this->crud->setValidation(StateRequest::class);
        $this->crud->removeSaveAction('save_and_preview');
        $this->crud->removeSaveAction('save_and_edit');
        $this->crud->removeSaveAction('save_and_new');

        $country_id = null;
        if (isset($_GET['country_id'])) {
            $country_id = $_GET['country_id'];
        } else {
            $State = State::where('id', Route::current()->parameter('id'))->first();
            if ($State) {
                $country_id = $State->country_id;
            }
        }
        $this->crud->addField([
            'type' => "hidden",
            'name' => 'country_id',
            'default' => $country_id
        ]);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function states(Request $request)
    {

        // NOTE: this is a Backpack helper that parses your form input into an usable array.
        // you still have the original request as `request('form')`
        $form = backpack_form_input();

        $options = State::query();

        if (isset($form['country_id'])) {
            $options = $options->where('country_id', $form['country_id']);
        }

        $results = $options->paginate(100);

        return $results;
    }
}
