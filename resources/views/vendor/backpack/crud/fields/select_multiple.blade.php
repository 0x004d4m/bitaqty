<!-- select multiple -->
@php
    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
    $field['allows_null'] = $field['allows_null'] ?? true;

    $field['value'] = old_empty_or_null($field['name'], collect()) ??  $field['value'] ?? $field['default'] ?? collect();

    if (is_a($field['value'], \Illuminate\Support\Collection::class)) {
        $field['value'] = $field['value']->pluck(app($field['model'])->getKeyName())->toArray();
    }

@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    {{-- To make sure a value gets submitted even if the "select multiple" is empty, we need a hidden input --}}
    <input type="hidden" name="{{ $field['name'] }}" value="" @if(in_array('disabled', $field['attributes'] ?? [])) disabled @endif />
    <select
    	class="form-control select2-multiple"
        name="{{ $field['name'] }}[]"
        @include('crud::fields.inc.attributes')
    	multiple>

    	@if (count($options))
    		@foreach ($options as $option)
				@if(in_array($option->getKey(), $field['value']))
					<option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
				@else
					<option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
				@endif
    		@endforeach
    	@endif

	</select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    <!-- include select2 css-->
    <link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <!-- include select2 js-->
    <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
    <script src="{{ asset('packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js') }}"></script>
    @endif
    @endpush



    <!-- include field specific select2 js-->
    @push('crud_fields_scripts')
    <script>
        // if nullable, make sure the Clear button uses the translated string
        document.styleSheets[0].addRule('.select2-selection__clear::after','content:  "{{ trans('backpack::crud.clear') }}";');


        /**
         *
         * This method gets called automatically by Backpack:
         *
         * @param  node element The jQuery-wrapped "select" element.
         * @return void
         */
        function bpFieldInitRelationshipSelectElement(element) {
            var form = element.closest('form');
            var $placeholder = element.attr('data-placeholder');
            var $modelKey = element.attr('data-model-local-key');
            var $fieldAttribute = element.attr('data-field-attribute');
            var $connectedEntityKeyName = element.attr('data-connected-entity-key-name');
            var $includeAllFormFields = element.attr('data-include-all-form-fields') == 'false' ? false : true;
            var $dependencies = JSON.parse(element.attr('data-dependencies'));
            var $multiple = element.attr('data-field-multiple')  == 'false' ? false : true;
            var $selectedOptions = typeof element.attr('data-selected-options') === 'string' ? JSON.parse(element.attr('data-selected-options')) : JSON.parse(null);
            var $allows_null = (element.attr('data-column-nullable') == 'true') ? true : false;
            var $allowClear = $allows_null;
            var $isFieldInline = element.data('field-is-inline');

            var $item = false;

            var $value = JSON.parse(element.attr('data-current-value'))

            if(Object.keys($value).length > 0) {
                $item = true;
            }
            var selectedOptions = [];
            var $currentValue = $item ? $value : {};

            //we reselect the previously selected options if any.
            Object.entries($currentValue).forEach(function(option) {
                selectedOptions.push(option[0]);
                $(element).val(selectedOptions);
            });

            if (!$allows_null && $item === false) {
                element.find('option:eq(0)').prop('selected', true);
            }

            $(element).attr('data-current-value',$(element).val());
            $(element).trigger('change');

            var $select2Settings = {
                    theme: 'bootstrap',
                    multiple: $multiple,
                    placeholder: $placeholder,
                    allowClear: $allowClear,
                    dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : document.body
                };
            if (!$(element).hasClass("select2-hidden-accessible"))
            {
                $(element).select2($select2Settings);
            }
        }
    </script>
    @endpush
    @endif
    {{-- End of Extra CSS and JS --}}
    {{-- ########################################## --}}

@push('after_scripts')
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2();
        });
    </script>
@endpush
