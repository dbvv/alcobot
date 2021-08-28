<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Title
    |--------------------------------------------------------------------------
    |
    | Displayed in title and header.
    |
    */

    'title' => 'Alcobot',

    /*
    |--------------------------------------------------------------------------
    | Admin Mini logo
    |--------------------------------------------------------------------------
    */
    'logo_mini' => 'A',

    /*
    |--------------------------------------------------------------------------
    | Admin Text on sidebar top menu
    |--------------------------------------------------------------------------
    */
    'menu_top' => 'Main menu',

    /*
    |--------------------------------------------------------------------------
    | Admin Logo
    |--------------------------------------------------------------------------
    |
    | Displayed in navigation panel.
    |
    */

    'logo' => '',

    /*
    |--------------------------------------------------------------------------
    | Admin URL prefix
    |--------------------------------------------------------------------------
    */

    'url_prefix' => 'admin',

    /*
     * Subdomain & Domain support routes
     */
    'domain' => false,

    /*
    |--------------------------------------------------------------------------
    | Middleware to use in admin routes
    |--------------------------------------------------------------------------
    |
    | In order to create authentication views and routes
    | don't forget to execute `php artisan make:auth`.
    | See https://laravel.com/docs/authentication
    |
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Env Editor
    |--------------------------------------------------------------------------
    | Url for env editor
    |
    */
    'env_editor_url' => 'env/editor',

    /*
     * Excluded keys
     */
    'env_editor_excluded_keys' => [
        'APP_KEY', 'DB_*',
    ],

    /*
     * Env editor middlewares
     */
    'env_editor_middlewares' => [],

    /*
     * Enable and show link in navigation
     * 'show_editor' is @deprecated
     */
    'enable_editor' => true,
    'env_keys_readonly' => false,
    'env_can_delete' => true,
    'env_can_add' => true,

    /*
     * --------------------------------------------------------------------------
     * Add your policy class here.
     * --------------------------------------------------------------------------
     */
    'env_editor_policy' => '',

    /*
     * --------------------------------------------------------------------------
     * DataTables state saving.
     * --------------------------------------------------------------------------
     */
    'state_datatables' => true,

    /*
     * --------------------------------------------------------------------------
     * Tabs state remember.
     * --------------------------------------------------------------------------
     */
    'state_tabs' => false,

    /*
     * --------------------------------------------------------------------------
     * Filters state remember in DataTables.
     * --------------------------------------------------------------------------
     */
    'state_filters' => false,

    /*
    |--------------------------------------------------------------------------
    | Authentication default provider
    |--------------------------------------------------------------------------
    |
    | @see config/auth.php : providers
    |
    */

    'auth_provider' => 'users',

    /*
    |--------------------------------------------------------------------------
    |  Path to admin bootstrap files directory
    |--------------------------------------------------------------------------
    |
    | Default: app_path('Admin')
    |
    */

    'bootstrapDirectory' => app_path('Admin'),

    /*
    |--------------------------------------------------------------------------
    |  Directory for uploaded images (relative to `public` directory)
    |--------------------------------------------------------------------------
    */

    'imagesUploadDirectory' => 'images/uploads',

    /*
    |--------------------------------------------------------------------------
    |  Use LazyLoad for AdminColumn::image in tables
    |  in `imageLazyLoadFile` insert path to file or `data:image/gif;base64,...`
    |--------------------------------------------------------------------------
    */

    'imageLazyLoad' => false,
    'imageLazyLoadFile' => 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',

    /*
    |--------------------------------------------------------------------------
    |  Allowed Extensions for uploaded images - array
    |--------------------------------------------------------------------------
    */

    'imagesAllowedExtensions' => [
        'jpe', 'jpeg', 'jpg', 'png', 'bmp', 'ico', 'gif',
    ],

    /*
    |--------------------------------------------------------------------------
    |  Allow to upload svg-files without required xml-header as image - boolean
    |--------------------------------------------------------------------------
    */

    'imagesAllowSvg' => false,

    /*
    |--------------------------------------------------------------------------
    |  Behavoir if file exists (default 'UPLOAD_HASH'). See in UploadController
    |--------------------------------------------------------------------------
    */

    'imagesUploadFilenameBehavior' => 'UPLOAD_HASH',

    /*
    |--------------------------------------------------------------------------
    |  Directory for uploaded files (relative to `public` directory)
    |--------------------------------------------------------------------------
    */

    'filesUploadDirectory' => 'files/uploads',

    /*
    |--------------------------------------------------------------------------
    |  Allowed Extensions for uploaded files - array
    |--------------------------------------------------------------------------
    */

    'filesAllowedExtensions' => [],

    /*
    |--------------------------------------------------------------------------
    |  Behavoir if file exists (default 'UPLOAD_HASH'). See in UploadController
    |--------------------------------------------------------------------------
    */

    'filesUploadFilenameBehavior' => 'UPLOAD_HASH',

    /*
    |--------------------------------------------------------------------------
    |  Admin panel template
    |--------------------------------------------------------------------------
    */

    'template' => SleepingOwl\Admin\Templates\TemplateDefault::class,

    /*
    |--------------------------------------------------------------------------
    |  Default date and time formats
    |--------------------------------------------------------------------------
    */

    'datetimeFormat' => 'd-m-Y H:i',
    'dateFormat'     => 'd-m-Y',
    'timeFormat'     => 'H:i',
    'timezone'       => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Editors
    |--------------------------------------------------------------------------
    |
    | Select default editor and tweak options if needed.
    |
    */

    'wysiwyg'     => [
        'default'   => 'ckeditor',

        /*
         * See http://docs.ckeditor.com/#!/api/CKEDITOR.config
         */
        'ckeditor'  => [
            'defaultLanguage' => config('app.locale'),
            'height'       => 200,
            'allowedContent' => true,
            'extraPlugins' => 'uploadimage,image2,justify,youtube,uploadfile',
            /*
             * WARNING!!!! CKEDITOR on D & D and UploadImageDialog
             * BY DEFAULT IMAGES WILL STORE TO imagesUploadDirectory = /images/uploads
             * 'uploadUrl'            => '/path/to/your/action',
             * 'filebrowserUploadUrl' => '/path/to/your/action',
             */

        ],

        /*
         * See https://www.tinymce.com/docs/
         */
        'tinymce'   => [
            'height' => 200,
        ],

        /*
         * See https://github.com/NextStepWebs/simplemde-markdown-editor
         */
        'simplemde' => [
            'hideIcons' => ['side-by-side', 'fullscreen'],
        ],

        /*
        * ver. 0.8.12
        * See https://summernote.org/
        * Need jQuery
        */
        'summernote' => [
            'height' => 200,
            'lang' => 'ru-RU',
            'codemirror' => [
                'theme' => 'monokai',
            ],
        ],

        /*
         * See https://ckeditor.com/docs/ckeditor5/latest/builds/guides/integration/configuration.html
         *
         * For using CKFinder with CKEditor 5 you must load additional js-file, see /app/Admin/bootstrap.php
         * See https://ckeditor.com/docs/ckeditor5/latest/features/image-upload/ckfinder.html#configuring-the-full-integration
         *
         * Be careful: CKEditor 5 haven't html source code button feature!
         * See https://github.com/ckeditor/ckeditor5/issues/592
         */
        'ckeditor5' => [
            'files' => [
                /*
                 * Use Classic build from CDN
                 * See https://ckeditor.com/ckeditor-5/download/
                 */
                'editor' => '//cdn.ckeditor.com/ckeditor5/23.1.0/classic/ckeditor.js',
                'translation' => '//cdn.ckeditor.com/ckeditor5/23.1.0/classic/translations/'.config('app.locale').'.js',
                /*
                 * Use Custom build with most-used additional plugins
                 * See https://ckeditor.com/ckeditor-5/online-builder/
                 */
                // 'editor' => '/packages/sleepingowl/ckeditor5/ckeditor.js',
                // 'translation' => '/packages/sleepingowl/ckeditor5/translations/' . config('app.locale') . '.js',
            ],

            'language' => config('app.locale'),

            'alignment' => [
                'options' => [
                    'left', 'right',
                ],
            ],

            'toolbar' => [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'blockQuote', 'link', 'bulletedList', 'numberedList', '|',
                'CKFinder', 'ImageUpload', 'imageTextAlternative', 'MediaEmbed', 'imageStyle:full', 'imageStyle:side', '|',
                'insertTable', 'tableColumn', 'tableRow', 'mergeTableCells', '|',
            ],

            'uploadUrl'                 => '/storage/images_admin',
            'filebrowserUploadUrl'      => '/storage/images_admin',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | DataTables
    |--------------------------------------------------------------------------
    |
    | Select default settings for datatable
    |
    */
    'datatables'  => [],

    /*
    |--------------------------------------------------------------------------
    | DataTables column highlight
    |--------------------------------------------------------------------------
    |
    | Highlight DataTables column on mouseover
    |
    */
    'datatables_highlight' => false,

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    |
    */
    'breadcrumbs' => true,

    /*
    |--------------------------------------------------------------------------
    | Autoupdate datatables
    |--------------------------------------------------------------------------
    |
    | Interval in minutes. Do not set too low.
    | dt_autoupdate_interval >= 1 and (int)
    | dt_autoupdate_class - custom class if need (can be null)
    | dt_autoupdate_color - color ProgressBar (can be null)
    |
    */
    'dt_autoupdate' => false,
    'dt_autoupdate_interval' => 5, //minutes
    'dt_autoupdate_class' => '',
    'dt_autoupdate_color' => '#dc3545',

    /*
    |--------------------------------------------------------------------------
    | Add scrolls button
    |--------------------------------------------------------------------------
    |
    */

    'scroll_to_top' => true,
    'scroll_to_bottom' => true,

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started.
    |
    */

    'aliases' => [
        // Components
        'Assets'              => KodiCMS\Assets\Facades\Assets::class,
        'PackageManager'      => KodiCMS\Assets\Facades\PackageManager::class,
        'Meta'                => KodiCMS\Assets\Facades\Meta::class, // will destroy
        'Form'                => Collective\Html\FormFacade::class,
        'HTML'                => Collective\Html\HtmlFacade::class,
        'WysiwygManager'      => SleepingOwl\Admin\Facades\WysiwygManager::class,
        'MessagesStack'       => SleepingOwl\Admin\Facades\MessageStack::class,

        // Presenters
        'AdminSection'        => SleepingOwl\Admin\Facades\Admin::class,
        'AdminTemplate'       => SleepingOwl\Admin\Facades\Template::class,
        'AdminNavigation'     => SleepingOwl\Admin\Facades\Navigation::class,
        'AdminColumn'         => SleepingOwl\Admin\Facades\TableColumn::class,
        'AdminColumnEditable' => SleepingOwl\Admin\Facades\TableColumnEditable::class,
        'AdminColumnFilter'   => SleepingOwl\Admin\Facades\TableColumnFilter::class,
        'AdminDisplayFilter'  => SleepingOwl\Admin\Facades\DisplayFilter::class,
        'AdminForm'           => SleepingOwl\Admin\Facades\Form::class,
        'AdminFormElement'    => SleepingOwl\Admin\Facades\FormElement::class,
        'AdminDisplay'        => SleepingOwl\Admin\Facades\Display::class,
        'AdminWidgets'        => SleepingOwl\Admin\Facades\Widgets::class,
    ],
];
