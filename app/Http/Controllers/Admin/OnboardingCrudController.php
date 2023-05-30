<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OnboardingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class OnboardingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Onboarding::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/onboarding');
        $this->crud->setEntityNameStrings(__('admin.onboarding'), __('admin.onboardings'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->column('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(OnboardingRequest::class);

        $this->crud->field('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
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
