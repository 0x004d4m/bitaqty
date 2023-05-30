crud.field('has_limit').onChange(function (field) {
    if (field.value == 1) {
        crud.field('clients').show().enable();
    } else {
        crud.field('clients').hide().disable();
    }
}).change();

crud.field('has_limit').onChange(function (field) {
    if (field.value == 1) {
        crud.field('vendors').show().enable();
    } else {
        crud.field('vendors').hide().disable();
    }
}).change();
