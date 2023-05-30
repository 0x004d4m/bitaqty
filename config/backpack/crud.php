<?php

/**
 * Backpack\CRUD preferences.
 */

return [

    /*
    |-------------------
    | TRANSLATABLE CRUDS
    |-------------------
    */

    'show_translatable_field_icon'     => true,
    'translatable_field_icon_position' => 'right', // left or right

    'locales' => [
        "ar" => "Arabic",
        'en' => 'English',
    ],

    'view_namespaces' => [
        'buttons' => [
            'crud::buttons', // falls back to 'resources/views/vendor/backpack/crud/buttons'
        ],
        'columns' => [
            'crud::columns', // falls back to 'resources/views/vendor/backpack/crud/columns'
        ],
        'fields' => [
            'crud::fields', // falls back to 'resources/views/vendor/backpack/crud/fields'
        ],
        'filters' => [
            'crud::filters', // falls back to 'resources/views/vendor/backpack/crud/filters'
        ],
    ],

];
