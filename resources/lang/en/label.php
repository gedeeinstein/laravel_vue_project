<?php

return [

    /*
    |--------------------------------------------------------------------------
    | language Label for dashboard
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during dashboard for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    // common words
    /** Login                    = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
    'enterEmailAddress'          => 'Email',
    'enterPassword'              => 'Password',
    'remember'                   => 'Remember Me',
    'login'                      => 'Login',
    'dashboard'                  => 'Dashboard',
    'superAdmin'                 => 'Super Admin',
    'admin'                      => 'Admin',
    'createNew'                  => 'Create New',
    'list'                       => 'List',
    'add'                        => 'Add New',
    'edit'                       => 'Edit',
    'required'                   => 'Required',
    'optional'                   => 'Optional',
    'update'                     => 'Update',
    'password'                   => 'Password',
    'showPassword'               => 'Show Password',
    'change'                     => 'Change',
    'register'                   => 'Register',
    'search'                     => 'Search',
    'all'                        => 'All',
    'delete'                     => 'Delete',
    'adminloginscreen'           => 'Administration Login',
    'newPassword'                => 'Enter new password to update your old password',
    'choosePasswordLength'       => 'Please choose a password with a minimum length of 8 characters.',
    'updatePasswordSentence'     => 'Click change button when you wish to update your password.',
    'jsConfirmDeleteData'        => 'Are you sure you want to delete this data?',
    'jsInfoDeletedData'          => 'Data has been successfully deleted!',
    'jsSorry'                    => 'Sorry, the data could not be deleted',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'success_create_message'     => 'Your entry has been successfully saved!',
    'failed_create_message'      => 'Sorry, we were unable to save your entry. Please check your entry and try again later',
    'success_update_message'     => 'Your update has been successfully saved!',
    'failed_update_message'      => 'Sorry, we were unable to save your update. Please check your update and try again later',
    'success_delete_message'     => 'Data has been successfully deleted!',
    'failed_delete_message'      => 'Sorry, the data could not be deleted',
    'common_error_message'       => 'Sorry, an error occurred',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'confirm_common'             => 'Would you like to continue?',
    'confirm_create'             => 'Add new items. Is it OK?',
    'confirm_edit'               => 'Apply the changes. Is it OK?',
    'confirm_delete'             => 'Delete the data. Is it OK?',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'report_downloaded'          => 'The report file has been downloaded',
    'report_failed'              => 'Sorry, the report could not be downloaded',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'approval_updated'           => 'The project approval has been updated',
    'approval_requested'         => 'The project approval has been requested',
    'approval_failed'            => 'Sorry, the project approval could not be updated',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'name'                       => 'Name',
    'name_kana'                  => 'Name (Kana)',
    'email'                      => 'Email',
    'admin_role_id'              => 'Admin role id',
    'email_verified_at'          => 'Email verified at',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'created_at'                 => 'Created at',
    'update_at'                  => 'Updated at',
    'action'                     => 'Action',
    /** Login History            = = = = = = = = = = = = = = = = = = = = = = */
    'historyLog'                 => 'Log Activity',
    'admin_id'                   => 'Admin ID',
    'activity'                   => 'Activity',
    'detail'                     => 'Detail',
    'ip'                         => 'IP Address',
    'last_access'                => 'Last Access',
    'IForgotMyPassword'          => 'I forgot my password',
    'language'                   => 'Language',

    // @TODO: Below are not arranged well and translated to JP, need some help from linguist
    'login_form'                 => 'Login Form',
    'last_update'                => 'Last Update',
    'image'                      => 'Image',
    'body'                       => 'Body', // Content textarea, not human body
    'title'                      => 'Title',
    'publish_date'               => 'Publish Date',
    'status'                     => 'Status',
    'news'                       => 'News',
    'company'                    => 'Company',
    'log_activity'               => 'Log Activity',
    'top_page'                   => 'TOP PAGE',
    'logout'                     => 'LOG OUT',
    'company_name'               => 'Company Name',
    'company_name_kana'          => 'Company Name (Kana)',
    'post_code'                  => 'Post Code',
    'address'                    => 'Address',
    'phone'                      => 'Phone',
    'access_time'                => 'Access Time',
    'user'                       => 'User',
    'user_role'                  => 'User Role',
    'company_info'               => 'Company Info',
    'userloginscreen'            => 'User Login',
    'master_values'              => 'Master Values',
    'master_region'              => 'Master Region',
    'success'                    => 'Success',
    'error'                      => 'Error',

    '$nav'                       => [
        'logout'                 => 'Logout'
    ],

    '$login'                     => [
        'admin'                  => [
            'meta'               => [
                'title'          => 'Administrator Login Page'
            ]
        ],
        'user'                   => [
            'meta'               => [
                'title'          => 'User Login Page'
            ]
        ],
        'reset'                  => [
            'title'              => 'Password Reset',
            'forgot'             => 'Forgot Password',
            'request'            => 'Send Password Reset Link',
            'password'           => 'Request New Password',
            'new'                => 'Register New Member',
            'email'              => 'Email Address'
        ],
        'confirm'                => [
            'title'              => 'Confirm Password',
            'message'            => 'Please confirm your password before continuing.',
            'submit'             => 'Confirm Password',
            'forgot'             => 'Forgot Your Password?',
            'password'           => 'Password'
        ],
        'label'                  => [
            'name'               => 'Name',
            'email'              => 'Email Address',
            'password'           => 'Password',
            'confirm'            => 'Confirm Password'
        ]
    ],

    '$log'                       => [
        'user'                   => 'Login User',
        'email'                  => 'Email',
        'activity'               => 'Activity',
        'ip'                     => 'IP address',
        'time'                   => 'Access Time'
    ],

    'key'                        => 'Key',
    'type'                       => 'Type',
    'value'                      => 'Value',
    'sort'                       => 'Sort',

    'prefecture'                 => 'Prefecture',
    'prefecture_code'            => 'Prefecture Code',
    'group_code'                 => 'Group Code',
    'name_alt'                   => 'Name',
    'name_kana'                  => 'Kana Name',
    'order'                      => 'Order',
    'designated_city'            => 'Designated City',
    'enabled'                    => 'Enabled',

    'individual'                 => 'Individual',
    '$company'                   => [
        'individual'             => 'Individual',
        'edit'                   => 'Edit Company',
        'list'                   => 'Company List',
        'tab'                    => [
            'about'              => 'About us',
            'users'              => 'Users'
        ],
        'type'                   => [
            'label'              => 'Type',
            'group'              => 'Company Group',
            'agent'              => 'Real Estate Agent',
            'contractor'         => 'Construction Contractor',
            'builder'            => 'House Maker',
            'profession'         => 'Profession',
            'advisor'            => 'Accounting Advisor',
            'bank'               => 'Bank',
            'other'              => 'Other'
        ],
        'form'                   => [
            'header'             => [
                'bank'           => 'Bank Information',
                'agent'          => 'Real Estate Agent Information',
                'group'          => 'Company Group Information',
                'account'        => 'Bank Account Information',
                'borrower'       => 'Borrower Information'
            ],
            'label'              => [
                'name'           => 'Name',
                'name_kana'      => 'Name Kana',
                'admin'          => 'Admin',
                'type'           => 'Type',
                'name_abbr'      => 'Name (Abbreviation)',
                'store'          => 'Store Name',
                'abbr'           => 'Abbr',
                'main'           => [
                    'address'    => 'Main Office (Location)',
                    'phone'      => 'Main Office (Phone)'
                ],
                'representative' => 'Representative',
                'license'        => [
                    'number'     => 'License Number',
                    'date'       => 'License Date',
                    'no'         => 'No.'
                ],
                'office'         => [
                    'label'      => 'Office Information',
                    'name'       => 'Office Information (Office Name)',
                    'address'    => 'Office Information (Location)',
                    'number'     => 'Office Information (Number)'
                ],
                'association'    => 'Guarantee Association',
                'depositary'     => [
                    'name'       => 'Depositary Name',
                    'address'    => 'Depositary Address'
                ],
                'account'        => [
                    'bank'       => 'Bank',
                    'type'       => 'Account Type',
                    'number'     => 'Account Number'
                ],
                'borrower'        => [
                    'bank'       => 'Bank',
                    'loan'       => 'Loan Amount Limit'
                ]
            ],
            'button'             => [
                'delete'         => 'Delete',
                'add'            => 'Add',
                'copy'           => 'Copy'
            ],
            'phrase'             => [
                'delete'         => [
                    'confirm'    => 'Are you sure to proceed?'
                ],
                'update'         => [
                    'success'    => 'Your changes have been successfully saved!'
                ]
            ],
            'account'            => [
                'express'        => 'Express',
                'regular'        => 'Regular'
            ]
        ]
    ],
    '$user'                      => [
        'list'                   => 'Users',
        'add'                    => 'Add User'
    ],
    'qamanager'                  => [
        'list'                   => 'Q&A Manager List',
        'category_index'         => 'Q&A Manager Category',
        'index'                  => 'Q&A Manager',
        'category'               => 'Category',
        'categorymm'             => 'Category Management',
        'question'               => 'Question',
        'input_type'             => 'Input Type',
        'choices'                => 'Choices',
        'status'                 => 'Status',
        'order'                  => 'Order',
        'name'                   => 'Name'
    ],
];
