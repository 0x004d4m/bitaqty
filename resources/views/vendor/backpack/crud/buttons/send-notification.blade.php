@if ($crud->hasAccess('delete'))
    <a href="javascript:void(0)"
        data-route="{{ url($crud->route . '/' . $entry->getKey() . '/send') }}"
        data-button-type="send"
        {!! $entry->is_sent==1?'':'onclick=\'sendNotification(this)\'' !!}
        class="btn btn-sm btn-link {{ $entry->is_sent==1?'text-danger':'' }}"

        ><i class="la la-paper-plane"></i> {{ __('admin.send') }}</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@loadOnce('send_button_script')
    @push('after_scripts') @if (request()->ajax())
    @endpush
@endif
<script>
    if (typeof sendNotification != 'function') {
        $("[data-button-type=send]").unbind('click');

        function sendNotification(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var route = $(button).attr('data-route');

            swal({
                title: "{!! __('admin.send_title') !!}",
                text: "{!! __('admin.send_text') !!}",
                icon: "warning",
                buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! __('admin.send') !!}"],
                dangerMode: true,
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: route,
                        type: 'get',
                        success: function(result) {
                            if (result == 1) {
                                // Redraw the table
                                if (typeof crud != 'undefined' && typeof crud.table !=
                                    'undefined') {
                                    // Move to previous page in case of deleting the only item in table
                                    if (crud.table.rows().count() === 1) {
                                        crud.table.page("previous");
                                    }

                                    crud.table.draw(false);
                                }

                                // Show a success notification bubble
                                new Noty({
                                    type: "success",
                                    text: "{!! '<strong>' .__('admin.sent') .'</strong><br>' .__('admin.sent_message') !!}"
                                }).show();

                                // Hide the modal, if any
                                $('.modal').modal('hide');
                            } else {
                                // if the result is an array, it means
                                // we have notification bubbles to show
                                if (result instanceof Object) {
                                    new Noty({
                                        type: "error",
                                        text: result.message
                                    }).show();
                                } else { // Show an error alert
                                    swal({
                                        title: "{!! __('admin.sent_error') !!}",
                                        text: "{!! __('admin.sent_error_message') !!}",
                                        icon: "error",
                                        timer: 4000,
                                        buttons: false,
                                    });
                                }
                            }
                        },
                        error: function(result) {
                            // Show an alert with the result
                            swal({
                                title: "{!! __('admin.sent_error') !!}",
                                text: "{!! __('admin.sent_error_message') !!}",
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                        }
                    });
                }
            });
        }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@if (!request()->ajax())
@endpush
@endif
@endLoadOnce
