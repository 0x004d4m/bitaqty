@if ($crud->hasAccess('import'))
  <a href="{{ url($crud->route.'/import') }}" class="btn btn-md btn-primary text-capitalize"><i class="la la-upload"></i> Import Excel</a>
@endif
