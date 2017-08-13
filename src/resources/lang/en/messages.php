<?php

return [
    'app_save'                => 'Save',
    'app_update'              => 'Update',
    'app_edit'                => 'Edit',
    'app_add_new'             => 'Add new',
    'app_delete'              => 'Delete',
    'app_no_entries_in_table' => 'No entries in table',
    'ops'                     => 'Operations',
    'menus'                   => [
        'title'  => 'Menus',
        'fields' => [
            'name' => 'Name',
        ],
    ],
    'pages' => [
        'title'  => 'Pages',
        'fields' => [
            'title'       => 'Title',
            'route'       => 'Route Name',
            'roles'       => 'Roles',
            'permissions' => 'Permissions',
            'menus'       => 'Menus',
            'locals'      => 'Available In',
            'template'    => 'Template',
        ],
    ],
    'permissions' => [
        'title'  => 'Permissions',
        'fields' => [
            'name' => 'Name',
        ],
    ],
    'roles' => [
        'title'  => 'Roles',
        'fields' => [
            'name'       => 'Name',
            'permission' => 'Permissions',
        ],
    ],
    'users' => [
        'title'  => 'Users',
        'fields' => [
            'name'           => 'Name',
            'email'          => 'Email',
            'roles'          => 'Roles',
            'permissions'    => 'Permissions',
        ],
    ],
];
