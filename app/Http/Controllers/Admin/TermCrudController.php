<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TermRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class TermCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Term::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/term');
        $this->crud->setEntityNameStrings(__('admin.term'), __('admin.terms'));
    }

    protected function setupListOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('term')->label(__('admin_fields.term'))->type('hidden');
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(TermRequest::class);

        $this->crud->field('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->field('term')->label(__('admin_fields.term'))->type('CKEditor');
    }

    protected function setupShowOperation()
    {
        $this->crud->column('name')->label(__('admin_fields.name'))->type('text');
        $this->crud->column('term')->label(__('admin_fields.term'))->type('textarea');
    }
}
