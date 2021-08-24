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
        'cart' => App\Http\TelegramCommands\CartCommand::class,
        'catalog' => App\Http\TelegramCommands\CatalogCommand::class,
        'category' => App\Http\TelegramCommands\CategoryCommand::class,
        'default' => App\Http\TelegramCommands\DefaultCommand::class,
        'empty_cart' => App\Http\TelegramCommands\EmptyCart::class,
        'order_create' => App\Http\TelegramCommands\CheckoutCommand::class,
        'pre_order' => App\Http\TelegramCommands\PreOrderCommand::class,
        'product' => App\Http\TelegramCommands\ProductCommand::class,
        'info' => App\Http\TelegramCommands\InfoMessageCommand::class,
    ],
];
