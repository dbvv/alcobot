<?php

return [
    // bot private api key
    'api_key' => env('TG_BOT_API_KEY', null),

    // bot user name
    'user_name' => env('TG_BOT_USERNAME', null),

    // telegram managers id. Can get with @userinfobot. Accept multiple values comma separated: 1,2,3
    'telegram_manager_id' => env('TG_MANAGER_ID', null),

    'keyboard' => [
        ['Каталог'],
        ['Корзина', "Доставка"],
        ['Онлайн-чат', "Позвонить"],
    ],

    /**
     * List of commands with hanlders
     */
    'commands' => [
        'catalog' => App\Http\TelegramCommands\CatalogCommand::class,
        'default' => App\Http\TelegramCommands\DefaultCommand::class,
        'product' => App\Http\TelegramCommands\ProductCommand::class,
        'category' => App\Http\TelegramCommands\CategoryCommand::class,
        'cart' => App\Http\TelegramCommands\CartCommand::class,
        'order_create' => App\Http\TelegramCommands\CheckoutCommand::class,
    ],
];
