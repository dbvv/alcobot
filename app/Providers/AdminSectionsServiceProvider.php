<?php

namespace App\Providers;

use App\AdminElements\CustomImageExplorer;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\Models\User::class => 'App\Http\Sections\Users',
        \App\Models\Category::class => 'App\Http\Sections\Categories',
        \App\Models\Product::class => 'App\Http\Sections\ProductSection',
        \App\Models\Page::class => 'App\Http\Sections\PagesSection',
        \App\Models\Order::class => 'App\Http\Sections\OrdersSection',
        \App\Models\TelegramUsers::class => 'App\Http\Sections\TelegramUsersSection',
    ];

        /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        $columnElementContainer = app('sleeping_owl.table.column');
        $columnElementContainer->add('imageSearchable', CustomImageExplorer::class);

        parent::boot($admin);
    }
}
